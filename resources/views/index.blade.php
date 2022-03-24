<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ag Zoom</title>

    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.3.0/css/bootstrap.css"/>
    {{--    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.3.0/css/react-select.css" />--}}
</head>
<body>
{{$signature}}
<div id="meetingSDKElement">
    <!-- Zoom Meeting SDK Rendered Here -->
</div>
<script src="https://source.zoom.us/2.3.0/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/2.3.0/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/2.3.0/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/2.3.0/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/2.3.0/lib/vendor/lodash.min.js"></script>
<!-- For Component View -->
<script src="https://source.zoom.us/2.3.0/zoom-meeting-embedded-2.3.0.min.js"></script>
<!-- For Client View -->
<script src="https://source.zoom.us/zoom-meeting-2.3.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsrsasign/8.0.20/jsrsasign-all-min.js"></script>
<script>
    const client = ZoomMtgEmbedded.createClient();
    let meetingSDKElement = document.getElementById('meetingSDKElement');

    client.init({
        debug: true,
        zoomAppRoot: meetingSDKElement,
        language: 'en-US',
        customize: {
            meetingInfo: [
                'topic',
                'host',
                'mn',
                'pwd',
                'telPwd',
                'invite',
                'participant',
                'dc',
                'enctype',
            ],
            toolbar: {
                buttons: [
                    {
                        text: 'Custom Button',
                        className: 'CustomButton',
                        onClick: () => {
                            console.log('custom button');
                        },
                    },
                ],
            },
        },
    });

    function generateSignature(sdkKey, sdkSecret, meetingNumber, role) {
        const iat = Math.round((new Date().getTime() - 30000) / 1000)
        const exp = iat + 60 * 60 * 2
        const oHeader = {alg: 'HS256', typ: 'JWT'}

        const oPayload = {
            sdkKey: sdkKey,
            mn: meetingNumber,
            role: role,
            iat: iat,
            exp: exp,
            appKey: sdkKey,
            tokenExp: iat + 60 * 60 * 2
        }

        const sHeader = JSON.stringify(oHeader)
        const sPayload = JSON.stringify(oPayload)
        var signature =  KJUR.jws.JWS.sign('HS256', sHeader, sPayload, sdkSecret)
        return signature
    }

    var meeting_id = 72848782756;
    var password = 'Kp7wHD';
    var key = 'EZS634hDHPhB9RbgcJZXB2oAv6eF3Vgt80PL';
    var secret = 'SxOoyo1M2BgS8kv4THUOyZTSXfGOyJ2ieEhe';
    var signature = generateSignature(key, secret, meeting_id, 1);

    console.log(signature)

    setTimeout(() => {
        client.join({
            apiKey: key,
            signature: signature,
            meetingNumber: meeting_id,
            password: password,
            userName: 'kaushal'
        })
    }, 2000)
</script>

</body>
</html>
