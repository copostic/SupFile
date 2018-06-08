<div id="toast"></div>
<div id="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="content"></div>
    </div>
</div>
<footer id="footer">
    <div class="container margcontainer">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <ul class="list-unstyled quick-links">
                    <li>
                        <a href="/"><img src="/public/img/logo/logo_supfile_blanc.png" width="150px"></a>
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <h5>Sitemap</h5>
                <ul class="list-unstyled quick-links">
                    <li><a href=": /auth/login"><i class="fa fa-angle-double-right"></i>Login</a></li>
                    <li><a href="/auth/register"><i class="fa fa-angle-double-right"></i>Register</a></li>
                    <li><a href="/explorer"><i class="fa fa-angle-double-right"></i>File Explorer</a></li>
                    <li><a href="/public/pdf/documentation.pdf"><i class="fa fa-angle-double-right"></i>Documentation</a></li>
                    <li><a href="/terms-and-conditions"><i class="fa fa-angle-double-right"></i>Terms of service</a></li>
                </ul>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                <ul class="list-unstyled list-inline social text-center">
                    <li class="list-inline-item"><a href="/auth/register"><i class="fa fa-facebook"></i></a></li>
                    <li class="list-inline-item"><a href="/auth/register"><i class="fa fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="/auth/register"><i class="fa fa-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="/auth/register"><i class="fa fa-google-plus"></i></a></li>
                    <li class="list-inline-item"><a href="/auth/register" target="_blank"><i class="fa fa-envelope"></i></a>
                    </li>
                </ul>
            </div>
            <hr>
        </div>
        <div class="row">
            <div>
                <p>SupFile est un site cr�� par des �tudiants de SUPINFO Nice et n'a en aucun cas un but commercial. Nous ne garantissons en aucun cas l'int�grit� de nos services. En plus, c'est heberg� sur un Raspberry Pi.</p>
                <p class="h6">
                    <a class="text-green ml-2" target="_blank">SUPFile</a> &copy 2018 - All right Reversed.
                </p>
            </div>
            <hr>
        </div>
    </div>
</footer>
<script type="text/javascript" src="/public/js/vendor/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/public/js/main.js"></script>
<script type="text/javascript">
    var uuid = '{$session.uuid|default:''}';
</script>
<script type="text/javascript" src="/public/js/explorer.js"></script>
</body>
</html>