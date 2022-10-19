<?php return array(
    'root' => array(
        'name' => 'dgwltd/dgwltd-blocks',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'f6435c08b35314677b15571eb856f8d87784e196',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'dgwltd/dgwltd-blocks' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'f6435c08b35314677b15571eb856f8d87784e196',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'squizlabs/php_codesniffer' => array(
            'pretty_version' => '3.7.1',
            'version' => '3.7.1.0',
            'reference' => '1359e176e9307e906dc3d890bcc9603ff6d90619',
            'type' => 'library',
            'install_path' => __DIR__ . '/../squizlabs/php_codesniffer',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
