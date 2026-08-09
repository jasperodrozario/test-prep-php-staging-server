<?php
// Expected variables:
// $inner_body
// $logo_url
// $bg_pattern_url
// $footer_reason
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif; }
            @media (prefers-color-scheme: dark) {
                .dark-fix, .dark-fix p, .dark-fix div, .dark-fix span, .dark-fix strong, td, th { color: #333333 !important; }
                .dark-fix strong { color: #004a99 !important; }
                .email-container { background-color: #f5f5f5 !important; background-image: url('<?php echo $bg_pattern_url; ?>') !important; }
            }
            .ExternalClass, .ReadMsgBody { width: 100%; background-color: #ffffff; }
        </style>
    </head>
    <body style="margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 0;">
            <tr>
                <td align="center">
                    <table width="560" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 560px; width: 100%; background-color: #e3e3e3; background-image: url('<?php echo $bg_pattern_url; ?>'); background-repeat: repeat; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <tr>
                            <td align="center" style="padding: 25px 0 20px 0; border-bottom: 1px solid #e0e0e0;">
                                <img src="<?php echo $logo_url; ?>" alt="ASVAB Tutoring" style="display: block; max-width: 80px; width: 80px; height: auto; margin: 0 auto;" border="0">
                            </td>
                        </tr>
                        <tr class="dark-fix">
                            <td style="padding: 30px 35px 35px 35px; background-color: #ffffff;">
                                <div style="font-size: 15px; line-height: 1.6; color: #333333;">
                                    <?php echo $inner_body; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 35px 25px 35px; background-color: #fafafa; border-top: 1px solid #e0e0e0;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td align="center">
                                            <p style="margin: 0 0 5px 0; font-size: 12px; color: #888888;">&copy; <?php echo date("Y"); ?> ASVAB Tutoring. All rights reserved.</p>
                                            <p style="margin: 0; font-size: 11px; color: #aaaaaa;"><?php echo $footer_reason; ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
