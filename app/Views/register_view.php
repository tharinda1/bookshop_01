<?php $page_session = \Config\Services::session(); ?>
<?php $validation = \Config\Services::validation(); ?>
<?php helper('form'); ?>


<?= $this->extend('layouts/base'); ?>
<?= $this->section('content'); ?>


        <?php if($page_session->getTempdata('success')):?>
                    <div id="hidemsg" class="alert alert-success">
                        <?=$page_session->getTempdata('success'); ?>
                    </div>
                <?php endif; ?>

        <?php if($page_session->getTempdata('error')):?>
                    <div id="hidemsg" class="alert alert-danger">
                        <?=$page_session->getTempdata('error'); ?>
                    </div>
                <?php endif; ?>
        
        
    <?= form_open('register/process', 'id = "reg-form"') ?>

        <h3>User Registration</h3>

        <label for="username">Username:</label>
        <?= form_input('username', set_value('username'), ['required' => true]) ?>
        <?php if (isset($validation) && $validation->hasError('username')) : ?>
            <div class="text-danger">
                <?= $validation->getError('username') ?>
            </div>
        <?php endif; ?>

        <label for="email">Email:</label>
        <?= form_input('email', set_value('email'), ['type' => 'email', 'required' => true]) ?>
        <?php if (isset($validation) && $validation->hasError('email')) : ?>
            <div class="text-danger">
                <?= $validation->getError('email') ?>
            </div>
        <?php endif; ?>

        <label for="password">Password:</label>
        <?= form_password('password', '', ['required' => true]) ?>
        <?php if (isset($validation) && $validation->hasError('password')) : ?>
            <div class="text-danger">
                <?= $validation->getError('password') ?>
            </div>
        <?php endif; ?>

        <hr>

        <h3>Additional Roles</h3>
        <label>
            <?= form_checkbox('is_author', '1', set_value('is_author'), ['id' => 'is_author', 'onchange' => 'toggleFields("author")']) ?>
            I am an Author
        </label>
        <div id="author_fields" style="display:none;">
            <label for="author_name">Author Name:</label>
            <?= form_input('author_name', set_value('author_name'), ['id' => 'author_name']) ?>
            <?php if (isset($validation) && $validation->hasError('author_name')) : ?>
                <div class="text-danger">
                    <?= $validation->getError('author_name') ?>
                </div>
            <?php endif; ?>
        </div>

        <label>
        <?= form_checkbox('is_publisher', '1', set_value('is_publisher'), ['id' => 'is_publisher', 'onchange' => 'toggleFields("publisher")']) ?>
        I am a Publisher
        </label>
        <div id="publisher_fields" style="display:none;">
            <label for="publisher_name">Publisher Name:</label>
            <?= form_input('publisher_name', set_value('publisher_name'), ['id' => 'publisher_name']) ?>
            <?php if (isset($validation) && $validation->hasError('publisher_name')) : ?>
                <div class="text-danger">
                    <?= $validation->getError('publisher_name') ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit">Register</button>

    <?= form_close() ?>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script>
    



    function toggleFields(role) {
        const checkbox = document.getElementById('is_' + role);
        const fields = document.getElementById(role + '_fields');
        const input = document.getElementById(role + '_name');

        if (checkbox.checked) {
            fields.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            fields.style.display = 'none';
            input.removeAttribute('required');
        }
    }

    $(document).ready(function() {
        // Fade in the message, delay for 3 seconds, and then fade it out
        $('#hidemsg').fadeIn('slow').delay(3000).fadeOut('slow');
    });
</script>
<?= $this->endSection(); ?>