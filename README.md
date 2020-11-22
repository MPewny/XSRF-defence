# XSRF Defence
Simple library made to protect Your web app from [XSRF](https://owasp.org/www-community/attacks/csrf) attempts.

## Features

- Verificating request with security token
- Verificating request with security sum
- Verificating request with request domain
- Verificating request with request source URL

## Download and Setup

1. clone or download `https://github/TheParanoik/XSRF-defence`
2. unzip if needed
3. check out the `demos` folder ;)
4. you **must** delete the `demos` folder if you are going to use the library 'live'
5. config `XSRF.php` the way you like (you can also leave it as is)

## Methods

| Name | Arguments | Description |
|------|-----------|-------------|
| createVerificationToken | $size (Intiger, Size of token in bytes, Default is 8) | Returns an unique secure token. Stores the Token in $_SESSION['token'] |
| createVerificationSum | none | Returns a md5 hash of User Agent, Server Domain and Salt. Stores the salt in $_SESSION['xsrfSalt'] |
| verifyByToken | none | Verifies if `token` stored in session and $_POST['token'] are identical. if yes returns true. |
| verifyBySum | none | Generates the sum on the server side and verifies if it's identical to $_POST['token'], if yes returns true |
| verifyByDomain | $domain (String, Expected referer domain, Default is false) | Verifies if referer domain and expected domain are identical,if yes returns true. If $domain is not set it will assume its $_SERVER['SERVER_NAME'] |
| verifyBySource | $expectedUrl (String, URL of the form you expect the request from, Default is false) | Verifies if the HTTP_REFERER is identical to expected URL, if yes returns true |
| displayError | none | Displays the error screen using error URL (XSRFErrorUrl) and message (error) |
| errorMessage | none | Returns error message |
