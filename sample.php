<?php
/*
    First, require the relevant classes:
*/
require_once 'openneo_auth_server.class.php';
require_once 'openneo_auth_signatory.class.php';

/*
    Next, let's make and configure an OpenneoAuthServer instance for future use.

    secret:
      The 'secret' is a secret key issued to you by OpenNeo. Don't let anyone else
      have this string - it would allow them to forge responses from your
      application!
   
    valid_apps: 
      This is a list of valid applications to which your server will send data.
      Right now, these only live on subdomains: myapp.openneo.net 
*/
$auth_server = new OpenneoAuthServer(array(
  'secret' => 'abcdefghijklmnopqrstuvwxyz',
  'valid_apps' => array('impress')
));

/*
    First, let's see if there are any commands in $_GET from the remote
    application. If not, is there a pre-existing session? If neither of those
    apply, too bad - we'll have to tell the user something went wrong.
*/

if($auth_server->setParams($_GET) || $auth_server->sessionExists()) {
  /*
      All right, good. We have a session to work with. Now, is there a logged
      in user?
      
      For reference, all items starting with MYAPP are pseudocode and should
      be replaced.
  */
  if(MYAPP_userIsLoggedIn()) {
    /*
        Great! Either the user was already logged in, or they just finished
        the login form. Let's send their data to the remote application, and
        then send the user there.
    */
    $auth_server->sendUserData(array(
      'id' => $MYAPP_user->id,
      'name' => $MYAPP_user->name
    ));
    $auth_server->redirect();
  } else {
    /*
        All right, the user needs to log in or sign up. Though you *could*
        just include() the login form here, it's better practice to redirect to
        the form and have it pass the user back when they're done. Just do what
        comes naturally - all that matters is that the user ends up here when
        they log in or sign up.
    */
    header('Location: /login');
  }
} else {
  // FIXME: Add a real message. You should probably do something like:
  // include('error.html');
  // to keep all your pretty HTML separate from the business logic here.
  echo 'Something went wrong processing your request. Sorry, sorry, sorry!';
}
?>
