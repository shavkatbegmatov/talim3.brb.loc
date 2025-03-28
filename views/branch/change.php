<div class="card">
    <form class="row g-0" method="POST" action="/branch/change/<?php echo $branch['id'] ?>">
        <div class="card-body">
            <h2 class="mb-4">Редактировать филиал</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

            <div>
                <label for="code" class="form-label required">Код</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Введите код" value="<?php echo $branch['code']; ?>">
            </div>
            <div class="mt-3">
                <label for="name" class="form-label required">Название</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Введите название" value="<?php echo $branch['name']; ?>">
            </div>
            <div class="mt-3">
                <label for="address" class="form-label required">Адрес</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Введите адрес" value="<?php echo $branch['address']; ?>">
            </div>
            <div class="mt-3">
                <label for="phone" class="form-label required">Номер телефона</label>
                <input type="text" class="form-control" id="phone" name="phone_mask" data-mask="+998 (00) 000-00-00" data-mask-visible="true" placeholder="+998 (00) 000-00-00" autocomplete="off" style="font-family: monospace;" value="998<?php echo $branch['phone']; ?>">
                <input type="hidden" id="phone_hidden" name="phone">
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Сохранить изменения
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const code = document.getElementById('code');
        const name = document.getElementById('name');
        const address = document.getElementById('address');
        const phone = document.getElementById('phone');
        const phoneHidden = document.getElementById('phone_hidden');
        const errorMessage = document.getElementById('error-message');
        let errorMessages = [];

        if (!code.value.trim()) {
            errorMessages.push('Пожалуйста, введите код.');
            code.classList.add('is-invalid');
            code.classList.add('is-invalid-lite');
        } else if (!/^\d{5}$/.test(code.value.trim())) {
            errorMessages.push('Код должен состоять из 5 цифр.');
            code.classList.add('is-invalid');
            code.classList.add('is-invalid-lite');
        } else {
            code.classList.remove('is-invalid');
            code.classList.remove('is-invalid-lite');
        }

        if (!name.value.trim()) {
            errorMessages.push('Пожалуйста, введите название.');
            name.classList.add('is-invalid');
            name.classList.add('is-invalid-lite');
        } else {
            name.classList.remove('is-invalid');
            name.classList.remove('is-invalid-lite');
        }

        if (!address.value.trim()) {
            errorMessages.push('Пожалуйста, введите адрес.');
            address.classList.add('is-invalid');
            address.classList.add('is-invalid-lite');
        } else {
            address.classList.remove('is-invalid');
            address.classList.remove('is-invalid-lite');
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
            phoneHidden.value = phone.value.replace(/\+998|\s|\(|\)|-/g, '');
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
