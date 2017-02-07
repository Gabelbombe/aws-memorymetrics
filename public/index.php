<?php // REQ: composer require aws/aws-sdk-php
date_default_timezone_set('America/Los_Angeles');

require dirname(__DIR__) . '/vendor/autoload.php';

    USE Aws\CloudWatch\CloudWatchClient AS Client;

$account  = str_replace("\n",'',shell_exec("aws ec2 describe-security-groups --group-names 'Default' --query 'SecurityGroups[0].OwnerId' --output text"));
$location = dirname(__DIR__) . "/output/$account-describemetrics.csv";
$profile  = shell_exec('echo $AWS_SECTION |xargs echo -n');

$client = New Client([
    'profile' => $profile,
    'region'  => 'us-east-1',
    'version' => 'latest',
]);

print_r($client->getMetricStatistics([
    'Namespace'  => 'System/Linux',
    'MetricName' => 'MemoryUtilization',
    'Dimensions' => [[
        'Name'   => 'InstanceId',
        'Value'  => 'i-1b4f819c',
    ]]
]));

