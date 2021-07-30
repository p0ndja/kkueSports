<div id="testMobile" class="d-none d-lg-block"></div>
<footer class="footer" id="footer">
    <div class="container" style="padding-top: 50px; padding-bottom: 10px;">            
            <div class="text-center text-white">
                <div class="mb-3"><h3 class="mb-0 font-weight-bold">KKU eSports</h3>ชมรมกีฬาอิเล็กทรอนิกส์ มหาวิทยาลัยขอนแก่น<br></div>
                <a class="btn btn-rounded btn-primary mr-1 ml-0 btn-md" href="https://www.facebook.com/kkueports" target="_blank"><i class="fab fa-facebook"></i> KKU eSports</a>
                <a class="btn btn-rounded btn-danger mr-1 ml-0 btn-md" href="mailto:kkuesports@gmail.com"><i class="fas fa-envelope"></i> kkuesports@gmail.com</a>
            </div>
        <div class="row">
            <div class="col-12 text-center mb-2">
                <small class="text-muted">Copyright &copy; <script>document.write(new Date().getFullYear())</script> KKU eSports, Khon Kaen University. All Right Reserved.</small><br>
                <small class='text-muted'><a href="https://www.pondja.com">PondJaᵀᴴ</a> • <a class="text-warning" data-toggle='modal' data-target='#webstatsModal'><i class="fas fa-chart-line"></i></a>
                <?php $end_time = microtime(TRUE); $time_taken =($end_time - $start_time)*1000; $time_taken = round($time_taken,5); echo 'Generated in ' . $time_taken . ' ms.';?>
                <?php if (!isLogin()) { ?>• <a href="../auth/login" class="text-muted">Login</a><?php } ?></small>
                </small>
            </div>
        </div>
    </div>
</footer>

<!--div class="loader"></div-->

<script type="text/javascript">
    // Tooltips Initialization
    $(document).ready(function () {
        $('.mdb-select').materialSelect();
        $('[data-toggle="tooltip"]').tooltip();
        $('.btn-floating').unbind('click');
        $('.fixed-action-btn').unbind('click');
        //$(".loader").delay(1500).fadeOut("slow");      
        attachFooter();
    });

    $('input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], input[type=date], input[type=time], textarea').each(function (element, i) {
        if ((element.value !== undefined && element.value.length > 0) || $(this).attr('placeholder') !== undefined) {
            $(this).siblings('label').addClass('active');
        } else {
            $(this).siblings('label').removeClass('active');
        }
        $(this).trigger("change");
    });
        
    const resizeObserver = new ResizeObserver(entries => attachFooter());
    resizeObserver.observe(document.body);

    var attach = false;
    function attachFooter() {
        var footerHeight = $("#footer").height();
        var bodyHeight = $(document.body).height();
        var windowHeight =  $(window).height();

        console.log(bodyHeight +"vs"+ windowHeight);

        if (!attach && (bodyHeight <= windowHeight)) {
            attach = true;
            $('#footer').attr('style', 'position: fixed!important; bottom: 0px;');
        } else if (attach && (bodyHeight + footerHeight > windowHeight)) {
            attach = false;
            $('#footer').removeAttr('style');
        }
    }

    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
    });

    $('.carouselsmoothanimated').on('slide.bs.carousel', function(e) {
        $(this).find('.carousel-inner').animate({
            height: $(e.relatedTarget).height()
        }, 500);
    });

    function getSearchParameters() {
        var prmstr = window.location.search.substr(1);
        return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
    }

    function transformToAssocArray( prmstr ) {
        var params = {};
        var prmarr = prmstr.split("&");
        for ( var i = 0; i < prmarr.length; i++) {
            var tmparr = prmarr[i].split("=");
            params[tmparr[0]] = tmparr[1];
        }
        return params;
    }

    var params = getSearchParameters();

    function backToTop() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }

    function hashtag() {
        if (window.location.hash) return window.location.hash.substring(1)
        return null;
    }
</script>
<?php mysqli_close($conn); ?>