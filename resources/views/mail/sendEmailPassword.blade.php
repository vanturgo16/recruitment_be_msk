<!DOCTYPE html>
<html>
<body>
    <span>
        @if($type == 'New')
            Dear New User,
            <br> We would like to inform you that your email has been used to register in the Dashboard Helpdesk Kemakmuran.
            <br> Use this email and password to log in to the dashboard:
        @else
            Dear User,
            <br> We have received a request to reset your password for the Dashboard Helpdesk Kemakmuran.
            <br> Use this email and new password to log in to the dashboard:
        @endif

        <br>
        <br>

        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span><b>Name</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $name }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Email</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $email }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>
                        @if($type == 'New')
                            Password
                        @else
                            New Password
                        @endif
                    </b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $password }}
                    </span>
                </td>
            </tr>
        </table>

        <br>
        <br>
        <br> [Dashboard HELPDESK] <br>

    </span>
</body>
</html>