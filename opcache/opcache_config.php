<?php
	echo '<pre>';
    //  获取缓存的配置信息
	var_dump(opcache_get_configuration());
	echo '<hr/>';
    //  获取缓存的状态信息
	var_dump(opcache_get_status());
	echo '</pre>';

    /**
     * opcache_get_configuration和opcache_get_status返回的都是一组有关缓存配置信息
     * 类似于上面的输出
array(3) {
  ["directives"]=>
  array(24) {
    ["opcache.enable"]=>
    bool(true)
    ["opcache.enable_cli"]=>
    bool(true)
    ["opcache.use_cwd"]=>
    bool(true)
    ["opcache.validate_timestamps"]=>
    bool(true)
    ["opcache.inherited_hack"]=>
    bool(true)
    ["opcache.dups_fix"]=>
    bool(false)
    ["opcache.revalidate_path"]=>
    bool(false)
    ["opcache.log_verbosity_level"]=>
    int(1)
    ["opcache.memory_consumption"]=>
    int(134217728)
    ["opcache.max_accelerated_files"]=>
    int(4000)
    ["opcache.max_wasted_percentage"]=>
    float(0.05)
    ["opcache.consistency_checks"]=>
    int(0)
    ["opcache.force_restart_timeout"]=>
    int(180)
    ["opcache.revalidate_freq"]=>
    int(60)
    ["opcache.preferred_memory_model"]=>
    string(0) ""
    ["opcache.blacklist_filename"]=>
    string(0) ""
    ["opcache.max_file_size"]=>
    int(0)
    ["opcache.error_log"]=>
    string(0) ""
    ["opcache.protect_memory"]=>
    bool(false)
    ["opcache.save_comments"]=>
    bool(true)
    ["opcache.load_comments"]=>
    bool(true)
    ["opcache.fast_shutdown"]=>
    bool(true)
    ["opcache.enable_file_override"]=>
    bool(false)
    ["opcache.optimization_level"]=>
    int(2147483647)
  }
  ["version"]=>
  array(2) {
    ["version"]=>
    string(5) "7.0.3"
    ["opcache_product_name"]=>
    string(12) "Zend OPcache"
  }
  ["blacklist"]=>
  array(0) {
  }
}
array(7) {
  ["opcache_enabled"]=>
  bool(true)
  ["cache_full"]=>
  bool(false)
  ["restart_pending"]=>
  bool(false)
  ["restart_in_progress"]=>
  bool(false)
  ["memory_usage"]=>
  array(4) {
    ["used_memory"]=>
    int(231368)
    ["free_memory"]=>
    int(133986360)
    ["wasted_memory"]=>
    int(0)
    ["current_wasted_percentage"]=>
    float(0)
  }
  ["opcache_statistics"]=>
  array(13) {
    ["num_cached_scripts"]=>
    int(2)
    ["num_cached_keys"]=>
    int(4)
    ["max_cached_keys"]=>
    int(7963)
    ["hits"]=>
    int(0)
    ["start_time"]=>
    int(1429153705)
    ["last_restart_time"]=>
    int(1429161635)
    ["oom_restarts"]=>
    int(0)
    ["hash_restarts"]=>
    int(0)
    ["manual_restarts"]=>
    int(81)
    ["misses"]=>
    int(2)
    ["blacklist_misses"]=>
    int(0)
    ["blacklist_miss_ratio"]=>
    float(0)
    ["opcache_hit_rate"]=>
    float(0)
  }
  ["scripts"]=>
  array(2) {
    ["D:\WWW\php-code\opcache\optest.php"]=>
    array(6) {
      ["full_path"]=>
      string(34) "D:\WWW\php-code\opcache\optest.php"
      ["hits"]=>
      int(0)
      ["memory_consumption"]=>
      int(4656)
      ["last_used"]=>
      string(24) "Thu Apr 16 13:20:35 2015"
      ["last_used_timestamp"]=>
      int(1429161635)
      ["timestamp"]=>
      int(1429161630)
    }
    ["D:\WWW\php-code\opcache\opcache_config.php"]=>
    array(6) {
      ["full_path"]=>
      string(42) "D:\WWW\php-code\opcache\opcache_config.php"
      ["hits"]=>
      int(0)
      ["memory_consumption"]=>
      int(2080)
      ["last_used"]=>
      string(24) "Thu Apr 16 14:28:32 2015"
      ["last_used_timestamp"]=>
      int(1429165712)
      ["timestamp"]=>
      int(1429150438)
    }
  }
}
     */
