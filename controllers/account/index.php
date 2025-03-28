<?php

$user = R::findOne('users', 'username = ?', [$data['username']]);