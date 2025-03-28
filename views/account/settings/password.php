<div class="card">
    <form class="row g-0" method="POST" action="/account/settings/password">
        <div class="card-body">
            <h2 class="mb-4">Сменить пароль</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

            <div>
                <label for="current_password" class="form-label">Текущий пароль</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Введите текущий пароль" autocomplete="off" maxlength="255">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Показать пароль" onclick="togglePasswordVisibility('current_password');">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path
                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
            <div class="mt-3">
                <label for="new_password" class="form-label">Новый пароль</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Введите новый пароль" autocomplete="off" maxlength="255">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Показать пароль" onclick="togglePasswordVisibility('new_password');">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path
                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                Сменить пароль
            </button>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility(id) {
        const passwordField = document.getElementById(id);
        const passwordFieldType = passwordField.getAttribute('type');
        passwordField.setAttribute('type', passwordFieldType === 'password' ? 'text' : 'password');
    }

    document.querySelector('form').addEventListener('submit', function (event) {
        const current_password = document.getElementById('current_password').value.trim();
        const new_password = document.getElementById('new_password').value.trim();
        const errorMessage = document.getElementById('error-message');
        let errorMessages = [];

        if (!current_password) {
            errorMessages.push('Пожалуйста, введите текущий пароль.');
        }

        if (!new_password) {
            errorMessages.push('Пожалуйста, введите новый пароль.');
        } else if (new_password.length < 8) {
            errorMessages.push('Длина нового пароля должна составлять не менее 8 символов.');
        }

        if (errorMessages.length > 0) {
            event.preventDefault();
            errorMessage.classList.remove('d-none');
            errorMessage.innerHTML = errorMessages.join('<br>');
        }
    });
</script>
