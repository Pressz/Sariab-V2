<?php

/**
 * App holder class
 */
class App {
    
    /**
     * 
     * Values are default values
     * 
     */
    private $Controller = 'HomeController';
    private $DefaultViewDirectoryOfController = 'Home';
    private $View = 'Index';
    private $Params = [];

    /**
     * GetPathInfo
     *
     * Returns URL requested by user
     * 
     * @return array
     */
    function  GetPathInfo(){
        
        $ActualLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        define('_RequestUri', $ActualLink);
        // $ParsedUrl = parse_url($ActualLink);

        // Use path info if exists
        // index.php/value1/value2
        if (isset($_SERVER['PATH_INFO']))
            $PathInfo = trim(
                $_SERVER['PATH_INFO']
                , '/');
        // Use query string if exists
        // index.php?/value1/value2
        else if (isset($_SERVER['QUERY_STRING']))
            $PathInfo = trim(
                $_SERVER['QUERY_STRING']
                , '/');
        // return null if no one exist
        else
            return [];
        
        // convert to array and return
        $Output = explode('/', $PathInfo);

        // Add other query strings to  the last parameter
        if ($PathInfo == '')
            return $Output;

        $LeftOver = substr($ActualLink,
        strpos($ActualLink, $PathInfo) + strlen($PathInfo));
        if ($LeftOver != '')
            array_push($Output, urldecode($LeftOver));

        // return
        return $Output;
    }


    /**
     * ThowError
     *
     * Throw low level errors which are log-able from web server
     * 
     * @return array
     */
    public function ThowError($HttpStatusCode, $Message = '') {
        switch ($HttpStatusCode)
        {
            // TODO: Use enums.
            case 400:
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 400 Bad Request');
                include('static/errors/HTTP400.html');
                exit;
            case 401:
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                include('static/errors/HTTP401.html');
                exit;
            case 403:
                header('HTTP/1.0 403 Forbidden');
                include('static/errors/HTTP403.html');
                exit;
            case 404:
                header('HTTP/1.0 404 Not Found');
                include('static/errors/HTTP404.html');
                exit;
            default:
                throw new Exception($Message);
                die();
        }
    }

    /**
     * GetUserFunctionArgumentNames
     * 
     * Gets attributes/paramters/arguments names
     * of a function in runtime dynamically.
     * 
     * 
     */
    function GetUserFunctionArgumentNames($pair){
        $ReflectClass = new ReflectionClass($pair[0]);
        $ReflectMethod = $ReflectClass->getMethod($pair[1]);
        $Paramters = $ReflectMethod->getParameters();
        $ParamterNames = array();
        foreach($Paramters as $Paramter)
        {
            array_push($ParamterNames, $Paramter->getName());
        }
        return $ParamterNames;
    }

    /**
     * __construct
     *
     * Class Constructor which is called on any request
     * 
     * @return void
     */
    function __construct(){
        // Get URL
        $URL = $this->GetPathInfo();
        
        // Routing
        // TODO: Use a routing mechanism which allows configuration

        // Set Controller
        if (isset($URL[0]) and $URL[0] != '')
        {
            $this->DefaultViewDirectoryOfController = $URL[0];
            $this->Controller = $URL[0].'Controller';
            unset($URL[0]);
        }
        // Set View
        if (isset($URL[1]) and $URL[1] != '')
        {
            $this->View = $URL[1];
            unset($URL[1]);
        }
        
        // Get other parameters
        $this->Params = array_values($URL);
        // Call the method form class
        $ControllerFilePath = 'Controller/' . $this->Controller.'.php';
        // If controller file does not exist
        if (!file_exists($ControllerFilePath))
	        $this->ThowError(404);
        // Include the controller file
        include($ControllerFilePath);
        // Create an instance of controller class
        $ClassObject = new $this->Controller();
       // Set the view folder
        $ClassObject->SetViewDirectory($this->DefaultViewDirectoryOfController);
        // Get the method
        $HttpMethod = $_SERVER['REQUEST_METHOD'];
        // Find the function
        $ControllerMethod = $this->View . $HttpMethod;
        // Call the method if exists
        if (!method_exists($ClassObject, $ControllerMethod))
            $this->ThowError(404);
        try
        {   
            $MethodDefinedParamters = $this->GetUserFunctionArgumentNames([$ClassObject, $ControllerMethod]);
            $sizeofPassedParams = sizeof($this->Params);

            // Use path_info instead of 'overloaded query string' parameters
            //          Controller/Action/Param1Value/?Param2=Param2Value
            //          index.php/Controller/Action/Param1Value/?Param2=Param2Value
            //          index.php?/Controller/Action/Param1Value/?Param2=Param2Value
            // In the three examples above, the Param2 is overloaded querystring
            // Which it will be replaced by function paramter name for simpler
            // programming of plugins and third-party software

            // Check if there is a 'overloaded query string' included
            // Logic: the last paramter must contain a question mark (?)
            if ($sizeofPassedParams > 0)
                if (strpos($this->Params[$sizeofPassedParams - 1], '?') !== false)
                {

                    // Parse overloaded query strings
                    $KeyValuePairs = explode( '&' , // Delimiter
                        substr($this->Params[$sizeofPassedParams -1]   // string
                            ,strpos(
                                $this->Params[$sizeofPassedParams - 1]
                                , '?'   // Begining of query string
                                ) + 1   // start position
                            )   // key pairs
                    );  // key pairs array

                    // Clear the last paramter value from overloaded
                    // query strings with substring
                    $this->Params[$sizeofPassedParams - 1] =
                    substr($this->Params[$sizeofPassedParams - 1], 0,
                    strpos($this->Params[$sizeofPassedParams - 1], '?'));
                    // replace '/' with ''
                    $this->Params[$sizeofPassedParams - 1] =
                    str_replace('/', '', $this->Params[$sizeofPassedParams - 1]);

                    $i = 0;
                    foreach ($MethodDefinedParamters as $MethodDefinedParamter)
                    {
                        $i++; // loop counter
                        // Skip previously defined values of
                        // method paramters from passed parameters
                        if ($i < $sizeofPassedParams)
                        {
                            // if (strpos($KeyValuePairs[$i], '=') === false)
                            continue;
                        }
                        // then decied for other paramters
                        // and set their values from query strings
                        // NOTE: OVERLOADED QUERY STRING VALUES
                        // WHICH ARE PASSED BEFORE AS PATH_INFO
                        // WILL NOT BE REPLACED.
                        
                        $found = false;
                        foreach ($KeyValuePairs as $KeyValue)
                        {
                            // If it's a valid keyval pair
                            // Logic: string contains equal mark (=)
                            if (strpos($KeyValue, '=') !== false)
                            {
                                if (explode('=', $KeyValue)[0] == $MethodDefinedParamter)
                                {
                                    $found = true;
                                    // Add the value to paramters
                                    $this->Params[$i-1] = explode('=', $KeyValue)[1];
                                }
                            }
                        }
                        if (!$found)
                        {
                            $this->Params[$i-1] = '';
                        }
                    }
                    // If overloaded query string was passed
                    // but not mentioned as a function argument
                    // load it as $_GET.

                    foreach ($KeyValuePairs as $KeyValue)
                    {
                        $found = false;
                        foreach ($MethodDefinedParamters as $MethodDefinedParamter)
                        {
                            if (strpos($KeyValue, '=') !== false)
                            {
                                if (explode('=', $KeyValue)[0] == $MethodDefinedParamter)
                                    $found = true;
                            }
                            else
                            {
                                if ($KeyValue == $MethodDefinedParamter)
                                    $found = true;
                            }

                        }
                        if (!$found)
                        {
                            if (strpos($KeyValue, '=') !== false)
                            {
                                $_GET[explode('=', $KeyValue)[0]] = explode('=', $KeyValue)[1];
                            }
                            else
                            {
                                $_GET[$KeyValue] = 'true';
                            }
                        }
                    }
                }

            // Call the view
            call_user_func_array([$ClassObject, $ControllerMethod], $this->Params);
        }
        catch (BadRequestException $exp ){ // On auth error
            $this->ThowError(400);
        } catch (AuthException $exp ){ // On auth error
            $this->ThowError(403);
        } catch (NotFoundException $exp ){ // on not found error
            $this->ThowError(404);
        } catch (UnauthException $exp) { // on unauthorized exception
            $this->ThowError(401);
        }
    }
}