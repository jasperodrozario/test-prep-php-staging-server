<?php
// Expected variables:
// $tutor_name
// $tutor_email
// $tutor_phone
// $tutor_state
// $tutor_message
// $tutor_date
// $tutor_source
// $course_name
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <tr>
        <td align="center" style="border: none; background-color: transparent;">
            <table width="100%" cellpadding="0" cellspacing="0" class="email-container" style="max-width: 580px; width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); overflow: hidden;">
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Name</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"><?php echo htmlspecialchars($tutor_name); ?></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Email</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <a href="mailto:<?php echo htmlspecialchars($tutor_email); ?>" style="color: #3182ce; text-decoration: none; font-weight: 500;"><?php echo htmlspecialchars($tutor_email); ?></a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Phone</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <a href="tel:<?php echo htmlspecialchars($tutor_phone); ?>" style="color: #3182ce; text-decoration: none; font-weight: 500;"><?php echo htmlspecialchars($tutor_phone); ?></a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">State</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"><?php echo htmlspecialchars($tutor_state); ?></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; vertical-align: top;">Message</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <div style="background-color: #f8fafc; border-left: 4px solid #3182ce; padding: 12px 16px; border-radius: 4px; font-style: italic; color: #4a5568; margin: 4px 0;">
                            <?php echo nl2br(htmlspecialchars($tutor_message)); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Date</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"><?php echo htmlspecialchars($tutor_date); ?></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Source</td>
                    <td style="width: 70%; border-bottom: 1px solid #edf2f7; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <span style="background-color: #ebf8ff; color: #2b6cb0; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($tutor_source); ?></span>
                    </td>
                </tr>
                <!-- <tr>
                    <td style="width: 30%; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Course Name</td>
                    <td style="width: 70%; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"><?php echo htmlspecialchars($course_name); ?></td>
                </tr>
                <tr>
                    <td style="width: 30%; padding: 14px 18px; text-align: left; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #4a5568; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Service Type</td>
                    <td style="width: 70%; padding: 14px 18px; text-align: left; font-size: 14px; color: #2d3748; line-height: 1.5; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;"><?php echo htmlspecialchars($service_type); ?></td>
                </tr> -->
            </table>
        </td>
    </tr>
</table>
