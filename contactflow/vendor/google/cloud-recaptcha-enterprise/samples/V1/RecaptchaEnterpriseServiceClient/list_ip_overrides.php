<?php
/*
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * GENERATED CODE WARNING
 * This file was automatically generated - do not edit!
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

// [START recaptchaenterprise_v1_generated_RecaptchaEnterpriseService_ListIpOverrides_sync]
use Google\ApiCore\ApiException;
use Google\ApiCore\PagedListResponse;
use Google\Cloud\RecaptchaEnterprise\V1\Client\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\IpOverrideData;
use Google\Cloud\RecaptchaEnterprise\V1\ListIpOverridesRequest;

/**
 * Lists all IP overrides for a key.
 *
 * @param string $formattedParent The parent key for which the IP overrides are listed, in the
 *                                format `projects/{project}/keys/{key}`. Please see
 *                                {@see RecaptchaEnterpriseServiceClient::keyName()} for help formatting this field.
 */
function list_ip_overrides_sample(string $formattedParent): void
{
    // Create a client.
    $recaptchaEnterpriseServiceClient = new RecaptchaEnterpriseServiceClient();

    // Prepare the request message.
    $request = (new ListIpOverridesRequest())
        ->setParent($formattedParent);

    // Call the API and handle any network failures.
    try {
        /** @var PagedListResponse $response */
        $response = $recaptchaEnterpriseServiceClient->listIpOverrides($request);

        /** @var IpOverrideData $element */
        foreach ($response as $element) {
            printf('Element data: %s' . PHP_EOL, $element->serializeToJsonString());
        }
    } catch (ApiException $ex) {
        printf('Call failed with message: %s' . PHP_EOL, $ex->getMessage());
    }
}

/**
 * Helper to execute the sample.
 *
 * This sample has been automatically generated and should be regarded as a code
 * template only. It will require modifications to work:
 *  - It may require correct/in-range values for request initialization.
 *  - It may require specifying regional endpoints when creating the service client,
 *    please see the apiEndpoint client configuration option for more details.
 */
function callSample(): void
{
    $formattedParent = RecaptchaEnterpriseServiceClient::keyName('[PROJECT]', '[KEY]');

    list_ip_overrides_sample($formattedParent);
}
// [END recaptchaenterprise_v1_generated_RecaptchaEnterpriseService_ListIpOverrides_sync]
