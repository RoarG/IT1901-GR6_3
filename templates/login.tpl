[[+include file="header.tpl"]]
<div id="login_holder">
    <h1>Logg inn</h1>
    [[+if isset($error)]]
        <p>Feil passord. Prøv igjen!</p>
    [[+/if]]
    <form action="" method="post" name="login_form" id="login_form">
        <input type="password" name="master_pw" id="master_pw" placeholder="Master passord" value="" />
        <div id="login_submit_holder">
            <input type="submit" name="login" value="Logg inn" id="login" />
        </div>
    </form>
</div>
[[+include file="footer.tpl"]]