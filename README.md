# H5P Cache Plugin

This plugin adds an MUC store for H5P Library files to improve performance on sites with slow or non local disk file stores.

## Installation & Usage

### Plugin Install

Place files in local/h5p_cache

Run Moodle upgrade

Patch H5P to allow class overrides, you can find the patch here: https://github.com/durzo/h5p-moodle-plugin/commit/52f250e0e8c6b910af5d02ac987559ffa42a85f3.patch

The Patch has been sent to H5P and is currently awaiting integration, hopefully one day it will be accepted.

Finally, add the class override to your config.php to make H5P use this plugin.
```
$CFG->mod_hvp_file_storage_class = '\local_h5p_cache\file_storage';
```

### Cache Configuration

Add a cache store mapping for local_h5p_cache in the MUC configuration: Site administration -> Plugins -> Caching -> Configuration

If using redis, ensure igbinary and gzip compression is enabled.

Files will be added to the cache when they are created or fetched from the file store.

### CLI Script

Optionally, run the cli/fill_cache.php script to load all existing H5P Library files into the cache.
