<?php if ($user) { ?>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="avatar">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    </span>
                </div>
                <div class="col">
                    <div class="card-title"><?php echo $user['username']; ?></div>
                    <?php if (user()): ?>
                        <?php if (user()['username'] == $user['username']): ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (user()): ?>
                <?php if (user()['username'] == $user['username']): ?>
                    <div class="card-actions">
                        <div class="btn-list">
                            <a href="/account/settings/password" class="btn btn-icon">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                </svg>
                            </a>
                            <a href="/account/logout" class="btn btn-icon btn-outline-danger">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="datagrid">
                <div class="datagrid-item">
                    <div class="datagrid-title">Адрес электоронной почты</div>
                    <div class="datagrid-content"><?php echo $user['email']; ?></div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Имя</div>
                    <div class="datagrid-content"><?php echo $user['name']; ?></div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Должность</div>
                    <div class="datagrid-content"><?php echo $user['position']; ?></div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Номер телефона</div>
                    <div class="datagrid-content">
                        <?php 
                            $phone = preg_replace("/[^0-9]/", "", $user['phone']);
                            echo "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { redirect('/'); } ?>