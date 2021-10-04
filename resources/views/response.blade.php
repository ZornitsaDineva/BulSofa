<body>



    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tbody>

            <tr>
                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                    <img src="{{ $message->embed(public_path() . '/images/category/brand2.png') }}" />
                    <p> {{$admin_message->response}} </p>
                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">{{$user->name}}, no further action is required.</p>
                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards,<br>BulSofa</p>
                </td>
            </tr>

        </tbody>

    </table>

</body>