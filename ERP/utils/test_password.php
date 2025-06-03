<?php
$input = 'prueba';
$hash = '$2y$10$6Uy/ZQ.kNSOLZ0PjBnp3Xec2KHQ8L5Fi8HXqEEr2sWlNdxVGzleZW';

var_dump(password_verify($input, $hash));
