<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
    <title><?= $this->renderSection('title') ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('public/css/style.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>
<body>
    <header>
    </header>
        <nav id="stick" class="navbar">
        <a href="<?= base_url('/') ?>" class="logo"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 720 720" style="enable-background:new 0 0 720 720;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#E0FBFC;}
	.st1{fill:#EE6C4D;}
	.st2{fill:#98C1D9;}
</style>
<g id="Layer_1">
	
		<image style="display:none;overflow:visible;" width="1024" height="1024" xlink:href="../../../../../Downloads/Bookshop online icon.png"  transform="matrix(0.7031 0 0 0.7031 0 0)">
	</image>
	<path class="st0" d="M168.5,172.8v286c0,0,84.8-35.7,181.8,31.7V220.8C350.3,220.8,322.7,158.5,168.5,172.8z"/>
	<path class="st0" d="M551,172.8v286c0,0-84.8-35.7-181.8,31.7V220.8C369.2,220.8,396.7,158.5,551,172.8z"/>
	<path class="st1" d="M408.4,405.3l-15.9-145c-0.7-6.3,6.3-10.5,11.5-6.9l120.4,82.9c5.1,3.5,3.9,11.4-2.1,13.2l-39,11.7l33,49.7
		c2.1,3.1,1.5,7.3-1.4,9.8L496.5,436c-3.4,2.8-8.5,2.1-10.9-1.6L449,379.1l-27.8,30.5C416.9,414.2,409.1,411.7,408.4,405.3z"/>
</g>
<g id="Layer_2">
	<path class="st2" d="M150.1,204.7h-11.3c-5.7,0-10.3,4.6-10.3,10.3l-0.3,282.6c0,5.7,4.7,10.4,10.4,10.4l144.4-1.2
		c7.9-0.1,15.7,2.4,22.2,6.9l11,7.8c26,18.3,60.7,18.6,86.9,0.5l12.5-8.6c6.3-4.3,13.8-6.7,21.5-6.7h143.3c5.7,0,10.3-4.6,10.3-10.3
		V215c0-5.7-4.6-10.3-10.3-10.3h-11.6v276c0,0-46-20.4-101.1-14.3C422,471.9,377.3,504,364,514.3c-2.3,1.8-5.5,1.7-7.7-0.3
		c-11.1-9.9-46.6-38.1-96.9-45.6c-61.3-9.2-109.3,13.3-109.3,13.3V204.7z"/>
</g>
</svg></a>
        <div class="search">
            <?= form_open(base_url('search'), ['method' => 'get', 'class' => 'form-inline']); ?>
            <?= form_input([
                'type'        => 'search',
                'name'        => 'q',
                'placeholder' => 'Search books 📖...',
                'aria-label'  => 'Search',
                'class'       => 'form-control mr-sm-2',
                'id'          => 'search-input'
            ]); ?>
            <?= form_button([
                'type'  => 'submit',
                'class' => 'btn btn-outline-success my-2 my-sm-0',
                'content' => 'Search'
            ]); ?>
            <?= form_close(); ?>
            <div id="search-suggestions"></div>
        </div>
        

        <div id="right-side">
            <div class="cart-icon-container">
                <a href="<?= base_url('/cart') ?>" class="nav-link text-nowrap">
                    <i class="bi bi-cart"></i>
                    <small>shopping cart</small>
                </a>
            </div>

            <div class="nav-links">
                <ul>
                    <?php if (auth()->loggedIn()): ?>
                        <li><span>Welcome, <?= auth()->user()->username ?></span></li>
                        <li><a href="<?= base_url('logout') ?>">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?= base_url('register') ?>">Register</a></li>
                        <li><a href="<?= base_url('login') ?>">Login</a></li>
                    <?php endif; ?>
                    <li><a href="<?= base_url('/') ?>">Home</a></li>
                    
                    <li><a href="<?= base_url('/contact') ?>">Contact</a></li>
                    <li><a href="<?= base_url('add_authors') ?>">Add Authors</a></li>
                </ul>
            </div>
        </div>            
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        </nav>
    
    <main>
        
        <?php if (session()->getTempdata('success')): ?>
            <div class="container mt-4">
                <div class="alert alert-success">
                    <?= session()->getTempdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getTempdata('error')): ?>
            <div class="container mt-4">
                <div class="alert alert-danger">
                    <?= session()->getTempdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>

<?= $this->renderSection('scripts') ?>

    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
  //This should be placed below CDN links
   $(document).ready(function() {
            $('.hamburger').on('click', function() {
                $(this).toggleClass('active');
                $('.nav-links').toggleClass('active');
            });

            $('.nav-links a').on('click', function() {
                if ($(window).width() <= 768) {
                    $('.hamburger').removeClass('active');
                    $('.nav-links').removeClass('active');
                }
            });
        });


</script>
<?= $this->renderSection('scripts') ?>
<script>
$(document).ready(function() {
    $('#search-input').on('keyup', function() {
        
        let query = $(this).val();
        if (query.length > 3) {
            
            $.ajax({
                url: '<?= base_url('ajax/suggestions') ?>',
                method: 'GET',
                data: { q: query },
                dataType: 'json',
                success: function(data) {
                    $('#search-suggestions').html(data.suggestions);
                }
            });
        } else {
            $('#search-suggestions').html('');
        }
    });

        $(document).on('click', function(e) {
        if (!$(e.target).closest('#search-input').length && !$(e.target).closest('#search-suggestions').length) {
            $('#search-suggestions').html('');
        }
    });

    $(document).on('click', '#search-suggestions .list-group-item', function(e) {
        e.preventDefault();
        var bookTitle = $(this).data('title');
        window.location.href = '<?= base_url('search') ?>?q=' + encodeURIComponent(bookTitle);
    });
});
</script>
<script>
$(function() { // Shorthand for $(document).ready()
    $('.add-to-cart-btn').on('click', function() {
        const bookId = $(this).data('book-id');
        const button = $(this);

        const csrfTokenName = $('meta[name="csrf-token-name"]').attr('content');
        const csrfTokenHash = $('meta[name="csrf-token-hash"]').attr('content');
        const postData = {
            book_id: bookId,
            quantity: 1
        };
        postData[csrfTokenName] = csrfTokenHash;

        $.ajax({
            url: '<?= site_url('cart/add') ?>',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(postData),
            success: function(data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                alert('An error occurred while adding the item to the cart.');
            }
        });
    });
});
</script>
</body>
</html>