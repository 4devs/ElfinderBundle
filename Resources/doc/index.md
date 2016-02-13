Getting Started With ElfinderBundle
===========================================

## Installation and usage

Installation and usage is a quick:

1. Download ElfinderBundle using composer
2. Enable the Bundle
3. Use the bundle with CKEditorBundle


### Step 1: Download ElfinderBundle using composer

Tell composer to download the bundle by running the command:

``` bash
$ composer require fdevs/elfinder-bundle
```

Composer will install the bundle to your project's `vendor/fdevs` directory.


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FDevs\ElfinderBundle\FDevsElfinderBundle(),
    );
}
```
and add config

``` yml
f_devs_elfinder:
    editor: ckeditor
    connector:
        local:
            driver: f_devs_elfinder.local.driver
            driver_options:
                path: 'uploads'
                rootDir: "%kernel.root_dir%/../web/"
            additional_images:
                XL:
                    prefix: 'XL'
                    width:  800
                    height: 800
                M:
                    prefix: 'M'
                    width:  300
                    height: 300
```


### Step 3: Use the bundle with [CKEditorBundle](https://github.com/egeloen/IvoryCKEditorBundle)

add config 

``` yml
ivory_ck_editor:
    configs:
        standard:
            filebrowserBrowseRoute: f_devs_elfinder
```
