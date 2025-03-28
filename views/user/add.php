<div class="card">
    <form class="row g-0" method="POST" action="/user/add">
        <div class="card-body">
            <h2 class="mb-4">Добавить администратора<?php if (isset($parentBranch)): ?> филиала <span class="text-blue"><?php echo $parentBranch['name']; ?></span> <?php endif; ?></h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

            <?php if (user()['type'] == '1'): ?>
                <div>
                    <label class="form-label required">Роль администратора</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="type" value="0" class="form-selectgroup-input" onchange="toggleBranchField()">
                            <span class="form-selectgroup-label">Сотрудник филиала</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="type" value="2" class="form-selectgroup-input" onchange="toggleBranchField()">
                            <span class="form-selectgroup-label">Администатор филиала</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="type" value="3" class="form-selectgroup-input" onchange="toggleBranchField()">
                            <span class="form-selectgroup-label">Андеррайтер</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="type" value="3" class="form-selectgroup-input" onchange="toggleBranchField()">
                            <span class="form-selectgroup-label">Работник Call Center</span>
                        </label>
                    </div>
                </div>
                
                <div class="mt-3" id="branch-section">
                    <label for="branch_id" class="form-label required">Филиал</label>
                    <select class="form-select" name="branch_id" id="branch_id">
                        <option value="" selected>—</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>">[<?php echo $branch['code']; ?>] <?php echo $branch['name']; ?> — <?php echo $branch['address']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <label for="username" class="form-label required">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Введите имя пользователя">
            </div>
            <div class="mt-3">
                <label for="email" class="form-label required">Адрес электронной почты</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Введите адрес электоронной почты">
            </div>
            <div class="mt-3">
                <label class="form-label required">Пароль</label>
                <div class="input-group input-group-flat">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="Show password" onclick="togglePasswordVisibility();">
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
                <label for="name" class="form-label required">Имя</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Введите имя">
            </div>
            <div class="mt-3">
                <label for="position" class="form-label required">Должность</label>
                <input type="text" class="form-control" id="position" name="position" placeholder="Введите должность">
            </div>
            <div class="mt-3">
                <label for="phone" class="form-label required">Номер телефона</label>
                <input type="text" class="form-control" id="phone" name="phone_mask" data-mask="+998 (00) 000-00-00" data-mask-visible="true" placeholder="+998 (00) 000-00-00" autocomplete="off" style="font-family: monospace;">
                <input type="hidden" id="phone_hidden" name="phone">
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Добавить администратора
            </button>
        </div>
    </form>
</div>

<script>
    function toggleBranchField() {
        const branchSection = document.getElementById('branch-section');
        const selectedRole = document.querySelector('input[name="type"]:checked').value;
        
        if (selectedRole === '3') {
            branchSection.style.display = 'none';
        } else {
            branchSection.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleBranchField();
    });

    const phone = document.getElementById('phone');
    const phoneHidden = document.getElementById('phone_hidden');

    function updateHiddenPhoneValue() {
        phoneHidden.value = phone.value.replace(/\+998|\s|\(|\)|-|_/g, '');
    }
    phone.addEventListener('input', updateHiddenPhoneValue);

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        passwordField.setAttribute('type', passwordFieldType === 'password' ? 'text' : 'password');
    }

    document.querySelector('form').addEventListener('submit', function (event) {
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const name = document.getElementById('name');
        const position = document.getElementById('position');
        const branch_id = document.getElementById('branch_id');
        const phone = document.getElementById('phone');
        const phoneHidden = document.getElementById('phone_hidden');
        const role = document.querySelector('input[name="type"]:checked');
        const errorMessage = document.getElementById('error-message');
        let errorMessages = [];

        if (!role) {
            errorMessages.push('Пожалуйста, выберите роль администратора.');
            document.querySelectorAll('input[name="type"]').forEach(input => {
                input.classList.add('is-invalid');
                input.classList.add('is-invalid-lite');
            });
        } else {
            document.querySelectorAll('input[name="type"]').forEach(input => {
                input.classList.remove('is-invalid');
                input.classList.remove('is-invalid-lite');
            });
        }

        if (!username.value.trim()) {
            errorMessages.push('Пожалуйста, введите имя пользователя.');
            username.classList.add('is-invalid');
            username.classList.add('is-invalid-lite');
        } else if (!/^[a-zA-Z0-9_]{4,16}$/.test(username.value.trim())) {
            errorMessages.push('Ваше имя пользователя должно содержать только буквенно-цифровые символы и символы подчеркивания и быть длиной от 4 до 16 символов.');
            username.classList.add('is-invalid');
            username.classList.add('is-invalid-lite');
        } else {
            username.classList.remove('is-invalid');
            username.classList.remove('is-invalid-lite');
        }

        if (!email.value.trim()) {
            errorMessages.push('Пожалуйста, введите адрес электронной почты.');
            email.classList.add('is-invalid');
            email.classList.add('is-invalid-lite');
        } else if (!/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(email.value.trim())) {
            errorMessages.push('Пожалуйста, введите действительный адрес электронной почты.');
            email.classList.add('is-invalid');
            email.classList.add('is-invalid-lite');
        } else {
            email.classList.remove('is-invalid');
            email.classList.remove('is-invalid-lite');
        }

        if (!password.value.trim()) {
            errorMessages.push('Пожалуйста, введите пароль.');
            password.classList.add('is-invalid');
            password.classList.add('is-invalid-lite');
        } else if (password.value.trim().length < 8) {
            errorMessages.push('Длина пароля должна составлять не менее 8 символов.');
            password.classList.add('is-invalid');
            password.classList.add('is-invalid-lite');
        } else {
            password.classList.remove('is-invalid');
            password.classList.remove('is-invalid-lite');
        }

        if (!name.value.trim()) {
            errorMessages.push('Пожалуйста, введите имя.');
            name.classList.add('is-invalid');
            name.classList.add('is-invalid-lite');
        } else {
            name.classList.remove('is-invalid');
            name.classList.remove('is-invalid-lite');
        }

        if (!position.value.trim()) {
            errorMessages.push('Пожалуйста, введите должность.');
            position.classList.add('is-invalid');
            position.classList.add('is-invalid-lite');
        } else {
            position.classList.remove('is-invalid');
            position.classList.remove('is-invalid-lite');
        }

        if (!branch_id.value.trim()) {
            errorMessages.push('Пожалуйста, выберите филиал.');
            branch_id.classList.add('is-invalid');
            branch_id.classList.add('is-invalid-lite');
        } else {
            branch_id.classList.remove('is-invalid');
            branch_id.classList.remove('is-invalid-lite');
        }

        if (!phone.value.trim()) {
            errorMessages.push('Пожалуйста, введите номер телефона.');
            phone.classList.add('is-invalid');
            phone.classList.add('is-invalid-lite');
        } else if (!/^\+998 \(\d{2}\) \d{3}-\d{2}-\d{2}$/.test(phone.value.trim())) {
            errorMessages.push('Пожалуйста, полностью заполните маску номера телефона.');
            phone.classList.add('is-invalid');
            phone.classList.add('is-invalid-lite');
        } else {
            phone.classList.remove('is-invalid');
            phone.classList.remove('is-invalid-lite');
        }

        if (errorMessages.length > 0) {
            event.preventDefault();
            errorMessage.classList.remove('d-none');
            
            let errorList = '<ul>';
            errorMessages.forEach(function(message) {
                errorList += '<li>' + message + '</li>';
            });
            errorList += '</ul>';

            errorMessage.innerHTML = errorList;
        } else {
            errorMessage.classList.add('d-none');
        }
    });
</script>
