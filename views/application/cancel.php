<div class="card">
    <form class="row g-0" method="POST" action="/application/cancel/<?php echo $data['id']; ?>">
        <div class="card-body">
            <h2 class="mb-4">Отменить заявку</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']['text'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div id="error-message" class="alert alert-danger d-none" role="alert"></div>

            <div>
                <label for="reason" class="form-label required">Причина отмены кредита</label>
                <textarea type="text" class="form-control" id="reason" name="reason" placeholder="Введите причину отмены кредита"></textarea>
            </div>

            <div class="btn-list mt-3">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Вы точно хотите отменить эту заявку?');">
                    Отменить заявку
                </button>
                <a href="/application" class="btn">Назад</a>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const reason = document.getElementById('reason');
        const errorMessage = document.getElementById('error-message');
        let errorMessages = [];

        if (!reason.value.trim()) {
            errorMessages.push('Пожалуйста, введите название.');
            reason.classList.add('is-invalid');
            reason.classList.add('is-invalid-lite');
        } else {
            reason.classList.remove('is-invalid');
            reason.classList.remove('is-invalid-lite');
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
