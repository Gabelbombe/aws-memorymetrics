<?php // REQ: composer require aws/aws-sdk-php
date_default_timezone_set('America/Los_Angeles');

require dirname(__DIR__) . '/vendor/autoload.php';

    USE Aws\CloudWatch\CloudWatchClient AS Client;

$account  = str_replace("\n",'',shell_exec("aws ec2 describe-security-groups --group-names 'Default' --query 'SecurityGroups[0].OwnerId' --output text"));
$location = dirname(__DIR__) . "/output/$account-describemetrics.csv";
$profile  = shell_exec('echo $AWS_SECTION |xargs echo -n');

    $start  = New DateTime;
    $clone  = Clone $start;
    $output = [];

    $clone->modify('-2 month');
    $csv = New CSVFile(dirname(__DIR__) . 'input/metrics.csv');

    while ($clone != $start)
    {
        $output[$clone->format('m-d-Y')] = [];

            $clone->modify('+1 day');
    }

$client = New Client([
    'profile' => $profile,
    'region'  => 'us-east-1',
    'version' => 'latest',
]);

$instanceId = 'i-1b4f819c';
$util = $client->getMetricStatistics([
    'Namespace'  => 'System/Linux',
    'MetricName' => 'MemoryUtilization',
    'Dimensions' => [[
        'Name'   => 'InstanceId',
        'Value'  => $instanceId,
    ]],

    'StartTime'  => $clone->modify('-2 month'),
    'EndTime'    => $start,
    'Period'     => 86400,         #seconds
    'Statistics' => ['Average'],
]);

foreach ($util->get('Datapoints') AS $array)
{
    $output[$array['Timestamp']->format('m-d-Y')][$instanceId] = $array['Average'];
}


print_r($output);