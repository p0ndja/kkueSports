<div style="font-weight: 500;" class="mx-auto mt-2 mb-2">           
    <a class="kku-light p-1" href="#home">Home</a>
    <a class="kku-light p-1" href="#about">About</a>
    <a class="kku-light p-1" href="#news">News</a>
    <a class="kku-light p-1" href="#event">Event</a>
    <a class="kku-light p-1" href="#footer">Contact</a>            
</div>
<script>
    $(function () {
        // ------------------------------------------------------- //
        // Multi Level dropdowns
        // ------------------------------------------------------ //
        $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).siblings().toggleClass("show");
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                $('.dropdown-submenu .show').removeClass("show");
            });
        });
    });
</script>