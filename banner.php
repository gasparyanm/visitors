<?php
    require './services/ImageService.php';
    require './services/UserDetectorService.php';

    // in case of we watch directly from script
    $siteUrl =  $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'];

    $userDetectorService = new UserDetectorService($siteUrl);
    $userDetectorService->saveVisitorData();

    $name = './images/banner.jpg';

    $imageService = new ImageService;
    $imageService->setImagePath($name);
    $imageService->showImage();

    exit;
?>
