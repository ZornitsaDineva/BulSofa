<body>



    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0"
        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tbody>

            <tr>
                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">

                    <?php
                    $url = LaravelLocalization::localizeUrl('/ad/' . $post->post_id);
                    ?>

                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">@lang('Hello,see this post:')</p>
                    <a href="{{ $url }}">{{ $url }}</a>

                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">@lang('Regards,')<br>{{ $user->name }}</p>
                </td>
            </tr>

        </tbody>

    </table>

</body>
