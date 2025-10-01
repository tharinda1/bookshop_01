<?= $this->extend('layouts/base'); ?>

<?= $this->section('content'); ?>
<?= form_open('register/process','id=>"reg-form"') ?>

    <h3>User Registration</h3>
    <?= service('validation')->listErrors() ?>

    <label for="username">Username:</label>
    <?= form_input('username', set_value('username'), ['required' => true]) ?>

    <label for="email">Email:</label>
    <?= form_input('email', set_value('email'), ['type' => 'email', 'required' => true]) ?>

    <label for="password">Password:</label>
    <?= form_password('password', '', ['required' => true]) ?>

    <hr>

    <h3>Additional Roles</h3>
    <label>
        <?= form_checkbox('is_author', '1', set_value('is_author'), ['id' => 'is_author', 'onchange' => 'toggleFields("author")']) ?>
        I am an Author
    </label>
    <div id="author_fields" style="display:none;">
        <label for="author_name">Author Name:</label>
        <?= form_input('author_name', set_value('author_name'), ['id' => 'author_name']) ?>
    </div>

    <label>
        <?= form_checkbox('is_publisher', '1', set_value('is_publisher'), ['id' => 'is_publisher', 'onchange' => 'toggleFields("publisher")']) ?>
        I am a Publisher
    </label>
    <div id="publisher_fields" style="display:none;">
        <label for="publisher_name">Publisher Name:</label>
        <?= form_input('publisher_name', set_value('publisher_name'), ['id' => 'publisher_name']) ?>
    </div>

    <button type="submit">Register</button>

<?= form_close() ?>

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
</script>

<?= $this->endSection(); ?>

