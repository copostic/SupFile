<section class="content" id="login">
    {*
  <section class="content">
      <a href="/auth/social?provider=Facebook">Login Facebook</a>
      <a href="/auth/social?provider=Twitter">Login Twitter</a>
      <a href="/auth/social?provider=Google">Login Google</a>

  *}
    {*    <form id="login">
            <input type="text" name="email" value="copostic@hotmail.fr">
            <input type="text" name="password" value="test">
            <input type="text" name="password_verify" value="test">
            <input type="text" name="first_name" value="Corentin">
            <input type="text" name="last_name" value="POSTIC">
            <input type="submit" value="Register">
        </form>*}{*

    <button id="sendForm">SEND</button>
    <div id="errorMessage"></div>
</section>*}

    <div class="container login-box">
        <div class="row">
            <div class="left col-md-5 col-sm-12">

                <h1>Sign in</h1>

                <input type="text" name="email" placeholder="E-mail" />
                <input type="password" name="password" placeholder="Password" />

                <input type="submit" name="signup_submit" value="Sign in" />
            </div>
            <div class="col-md-2 col-sm-12"><div class="or-container">OR</div></div>

            <div class="right col-md-5 col-sm-12">
                <ul class="loginwith">
                    <li>
                        <a href="/auth/social?provider=Facebook" class="social-signin facebook">Log in with facebook</a>
                    </li>
                    <li>
                        <a href="/auth/social?provider=Twitter" class="social-signin twitter">Log in with Twitter</a>
                    </li>
                    <li>
                        <a href="/auth/social?provider=Google" class="social-signin google">Log in with Google+</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>




</section>