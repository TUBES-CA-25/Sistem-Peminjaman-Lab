<?php

require_once 'core/Constants.php';
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'config/Database.php';

// Check if we need to load legacy Base URL config (optional, but good for transition)
if (file_exists('config/App.php')) {
    // We already defined BASE_URL in Constants.php, so we might skip this or ensure no conflict.
    // For now, let's just ignore the old file to avoid re-definition errors.
}
