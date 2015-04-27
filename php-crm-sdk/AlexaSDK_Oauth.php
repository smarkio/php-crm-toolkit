<?php
/*
if (isset($_SESSION["bearer"]))
    {

    echo "Token expires: ".date("H:i:s",$_SESSION["bearer"]["expires_on"] - time());

    if (isset($_SESSION["bearer"]) && ($_SESSION["bearer"]["expires_on"] - time()) <= 0){
        $resource = "Microsoft.CRM";
        $tenantID = "367da737-497d-4f24-8e4f-5903b0fe6c35";
        $clientID = "420918c9-e31e-4407-a48e-de24aca49ec0";
        $RedirectUri = "https://wpsdk.azurewebsites.net/reply";
        $refresh_token = $_SESSION["bearer"]["refresh_token"];

        // api_version=1.0 causes an error
        $url = "https://login.windows.net/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/token";

        $requestBody = "grant_type=refresh_token&refresh_token=".$refresh_token."&client_id=".$clientID."&redirect_uri=".urlencode($RedirectUri)."&resource=".urlencode($resource)."&client_secret=".urlencode("51FdNObvSHV1nIZ2Z8N3wK8beCtHvcasx0qB375V1A0=");

        $headers = array(     
            "GET "."/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/token"." HTTP/1.1",
            "Host: login.windows.net",
            "Content-type: application/x-www-form-urlencoded;charset=UTF-8",
            "Content-length: " . strlen($requestBody),
        );

        $cURLHandle = curl_init();
        curl_setopt($cURLHandle, CURLOPT_URL, $url);
        curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURLHandle, CURLOPT_TIMEOUT, 60);
        curl_setopt($cURLHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cURLHandle, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($cURLHandle, CURLOPT_SSLVERSION, 3);
        curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($cURLHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($cURLHandle, CURLOPT_POST, 1);
        curl_setopt($cURLHandle, CURLOPT_POSTFIELDS, $requestBody);

        $curlErrno = curl_errno($cURLHandle);
        if($curlErrno){
            $curlError = curl_error($cURLHandle);
            throw new Exception($curlError);
        }

        $response = curl_exec($cURLHandle);

        $resp = json_decode($response, true );

         $_SESSION["bearer"]["access_token"] = $resp["access_token"];
         $_SESSION["bearer"]["refresh_token"] = $resp["refresh_token"];
         $_SESSION["bearer"]["expires_on"] = $resp["expires_on"];
         $_SESSION["bearer"]["expires_in"] = $resp["expires_in"];
    }

    if(isset($_SESSION["bearer"]) && ($_SESSION["bearer"]["expires_on"] - time()) > 0){
            $access_token = $_SESSION["bearer"]["access_token"];

            $url = "https://hqsoft.api.crm5.dynamics.com/XRMServices/2011/Organization.svc/web";

            $request = "<s:Envelope xmlns:s='http://schemas.xmlsoap.org/soap/envelope/'>
                            <s:Body>
                            <Execute xmlns='http://schemas.microsoft.com/xrm/2011/Contracts/Services' xmlns:i='http://www.w3.org/2001/XMLSchema-instance'>
                                <request i:type='b:WhoAmIRequest' xmlns:a='http://schemas.microsoft.com/xrm/2011/Contracts' xmlns:b='http://schemas.microsoft.com/crm/2011/Contracts'>
                                <a:Parameters xmlns:c='http://schemas.datacontract.org/2004/07/System.Collections.Generic' />
                                <a:RequestId i:nil='true' />
                                <a:RequestName>WhoAmI</a:RequestName>
                                </request>
                            </Execute>
                            </s:Body>
                        </s:Envelope>";

            $headers = array(     
                "POST ". "/XRMServices/2011/Organization.svc/web"." HTTP/1.1",
                "Host: hqsoft.api.crm5.dynamics.com",
                "Authorization: Bearer ".$access_token,
                "Content-Type: text/xml; charset=utf-8",
                "SOAPAction: http://schemas.microsoft.com/xrm/2011/Contracts/Services/IOrganizationService/Execute",
                "Content-length: " . strlen($request)
            );

            $cURLHandle = curl_init();
            curl_setopt($cURLHandle, CURLOPT_URL, $url);
            curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cURLHandle, CURLOPT_TIMEOUT, 60);
            curl_setopt($cURLHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cURLHandle, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($cURLHandle, CURLOPT_SSLVERSION, 3);
            curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($cURLHandle, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($cURLHandle, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0"); 
            $cookie_file = "cookie.txt";
            curl_setopt($cURLHandle, CURLOPT_COOKIESESSION, true);
            curl_setopt($cURLHandle, CURLOPT_COOKIEFILE, $cookie_file);
            curl_setopt($cURLHandle, CURLOPT_COOKIEJAR, $cookie_file);
            curl_setopt($cURLHandle, CURLOPT_POST, 1);
            curl_setopt($cURLHandle, CURLOPT_POSTFIELDS, $request);

            $response = curl_exec($cURLHandle);

            $content_type = curl_getinfo($cURLHandle);

            $curlErrno = curl_errno($cURLHandle);
            if($curlErrno){
                $curlError = curl_error($cURLHandle);
                throw new Exception($curlError);
            }

            $returnValue = preg_replace('/(<)([a-z]:)/', '<', preg_replace('/(<\/)([a-z]:)/', '</', $response));

            $soap = new DomDocument();
            $soap->loadXML($returnValue);

            $userid = $soap->getElementsbyTagName("value")->item(0)->textContent;

            $url = "https://hqsoft.crm5.dynamics.com/XRMServices/2011/OrganizationData.svc/SystemUserSet(guid'".$userid."')/FullName";

            $request = "";

            $headers = array(     
                "GET ". "/XRMServices/2011//XRMServices/2011/OrganizationData.svc/SystemUserSet(guid'".$userid."')/FullName"." HTTP/1.1",
                "Host: hqsoft.crm5.dynamics.com",
                "Authorization: Bearer ".$access_token,
                "Content-Type: application/json; charset=utf-8",
                "Accept: application/json; charset=utf-8",
                "Content-length: " . strlen($request)
            );

            $cURLHandle = curl_init();
            curl_setopt($cURLHandle, CURLOPT_URL, $url);
            curl_setopt($cURLHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cURLHandle, CURLOPT_TIMEOUT, 60);
            curl_setopt($cURLHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cURLHandle, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($cURLHandle, CURLOPT_SSLVERSION, 3);
            curl_setopt($cURLHandle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($cURLHandle, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($cURLHandle);

            $content_type = curl_getinfo($cURLHandle);

            $curlErrno = curl_errno($cURLHandle);
            if($curlErrno){
                $curlError = curl_error($cURLHandle);
                throw new Exception($curlError);
            }                             

            $resp = json_decode($response, true );

            echo "<p>Full Name: ".$resp["d"]["FullName"]."</p>";
    }else{
        $tenantID = "367da737-497d-4f24-8e4f-5903b0fe6c35";
        $clientID = "420918c9-e31e-4407-a48e-de24aca49ec0";

        $state = "943e2a91-a3f4-49a2-964b-0ccaffef82d3";

        //$resource = "https://hqsoft.crm5.dynamics.com";
        //resource can be or direct url or Microsoft.CRM
        $resource = "Microsoft.CRM";
        $RedirectUri = "https://wpsdk.azurewebsites.net/reply";

        // State parameter is optional, but recomended
        $constructUrl = "https://login.windows.net/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/authorize?api-version=1.0&response_type=code&client_id=".$clientID."&resource=".urlencode($resource)."&redirect_uri=".urlencode($RedirectUri)."&state=".$state;

        //$constructUrl = "https://login.windows.net/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/authorize?api-version=1.0&response_type=code&client_id=".$clientID."&resource=".urlencode($resource)."&redirect_uri=".urlencode($RedirectUri);

        echo "<a href='".$constructUrl."'> Connect To CRM with Authorization Code</a>";
    }
}else{
    $tenantID = "367da737-497d-4f24-8e4f-5903b0fe6c35";
        $clientID = "420918c9-e31e-4407-a48e-de24aca49ec0";

        $state = "943e2a91-a3f4-49a2-964b-0ccaffef82d3";

        //$resource = "https://hqsoft.crm5.dynamics.com";
        //resource can be or direct url or Microsoft.CRM
        $resource = "Microsoft.CRM";
        $RedirectUri = "https://wpsdk.azurewebsites.net/reply";

        // State parameter is optional, but recomended
        $constructUrl = "https://login.windows.net/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/authorize?api-version=1.0&response_type=code&client_id=".$clientID."&resource=".urlencode($resource)."&redirect_uri=".urlencode($RedirectUri)."&state=".$state;

        //$constructUrl = "https://login.windows.net/367da737-497d-4f24-8e4f-5903b0fe6c35/oauth2/authorize?api-version=1.0&response_type=code&client_id=".$clientID."&resource=".urlencode($resource)."&redirect_uri=".urlencode($RedirectUri);

        echo "<a href='".$constructUrl."'> Connect To CRM with Authorization Code</a>";
}
                    
                     