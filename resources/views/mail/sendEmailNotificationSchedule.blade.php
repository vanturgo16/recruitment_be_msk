<!DOCTYPE html>
<html>
<body>
    <span>
        Dear {{ $mailData['candidate_name'] }},
        <br> Congratulation you moved to <b>{{ $mailData['phase'] }}</b> session
        <br> Please see the details below:

        <br>
        <br>

        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span><b>Position Applied</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $mailData['position_applied'] }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Location</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $mailData['location'] }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Date</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ date('Y-m-d H:i:s', strtotime($mailData['date'])); }}
                    </span>
                </td>
            </tr>
        </table>
        <br>
        {{ $mailData['message'] }}
        <br>
        <br>
        <br> [HUMAN RESOURCE MSK] <br>

    </span>
</body>
</html>