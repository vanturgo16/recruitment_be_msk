<!DOCTYPE html>
<html>
<body>
    <span>
        Dear {{ $mailData['candidate_name'] }},
        <br> We would like to inform you that your application job has a new progress:

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
                    <span><b>Status</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $mailData['status'] }}
                    </span>
                </td>
            </tr>
        </table>

        {{ $mailData['message'] }}
        <br>
        <br>
        <br> [HUMAN RESOURCE MSK] <br>

    </span>
</body>
</html>