const https = require('https');
const crypto = require('crypto');

const subscriptionKey = process.env.MTN_MOMO_SUBSCRIPTION_KEY;
const apiUser = crypto.randomUUID();

if (!subscriptionKey) {
    throw new Error('MTN_MOMO_SUBSCRIPTION_KEY is required.');
}

function makeRequest(options, postData) {
    return new Promise((resolve, reject) => {
        const request = https.request(options, response => {
            let body = '';
            response.on('data', chunk => body += chunk);
            response.on('end', () => resolve({ status: response.statusCode, body }));
        });
        request.on('error', reject);
        if (postData) request.write(postData);
        request.end();
    });
}

async function run() {
    const callbackHost = process.env.MTN_MOMO_CALLBACK_HOST || 'webhook.site';
    const userBody = JSON.stringify({ providerCallbackHost: callbackHost });
    const userResponse = await makeRequest({
        hostname: 'sandbox.momodeveloper.mtn.com',
        path: '/v1_0/apiuser',
        method: 'POST',
        headers: {
            'X-Reference-Id': apiUser,
            'Ocp-Apim-Subscription-Key': subscriptionKey,
            'Content-Type': 'application/json',
            'Content-Length': Buffer.byteLength(userBody),
        },
    }, userBody);

    if (userResponse.status !== 201) {
        throw new Error(`API user creation failed (${userResponse.status}): ${userResponse.body}`);
    }

    const keyResponse = await makeRequest({
        hostname: 'sandbox.momodeveloper.mtn.com',
        path: `/v1_0/apiuser/${apiUser}/apikey`,
        method: 'POST',
        headers: {
            'Ocp-Apim-Subscription-Key': subscriptionKey,
            'Content-Length': 0,
        },
    });

    if (keyResponse.status !== 201) {
        throw new Error(`API key creation failed (${keyResponse.status}): ${keyResponse.body}`);
    }

    const apiKey = JSON.parse(keyResponse.body).apiKey;
    if (!apiKey) throw new Error('MTN did not return an API key.');

    process.stdout.write(JSON.stringify({ apiUser, apiKey }));
}

run().catch(error => {
    console.error(error.message);
    process.exit(1);
});
