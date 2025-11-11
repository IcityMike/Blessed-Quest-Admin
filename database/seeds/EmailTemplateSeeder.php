<?php

use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleEmailTemplate = [
            [
                'name' => 'Registration verification email',
                'subject' => 'Verify email address',
                'body' => '<p>[textAboveLink]</p><p>Please click the button below to verify your email address.</p><p>[/textAboveLink] [textBelowLink]</p><p>If you did not create an account, no further action is required.</p><p>[/textBelowLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Admin welcome email',
                'subject' => 'Welcome to MoneyApp',
                'body' => '<h1>Welcome to MoneyApp</h1><p><b>Please login using below details.</b></p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Password reset email',
                'subject' => 'Reset forgotten password',
                'body' => '<p>[textAboveLink]</p><p>You are receiving this email because we received a password reset request for your account.</p><p>[/textAboveLink] [textBelowLink]</p><p>This password reset link will expire in 60 minutes.</p><p>If you did not request a password reset, no further action is required.</p><p>[/textBelowLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Password change notification email',
                'subject' => 'Your password has changed',
                'body' => '<p>You have successfully reset your password.<br />You can now login with your new password using below link.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Update email verification',
                'subject' => 'Verify your email address',
                'body' => '<p>[textAboveLink]</p><p>Please click the button below to verify your email address.</p><p>[/textAboveLink] [textBelowLink]</p><p>If you did not create an account, no further action is required.</p><p>[/textBelowLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Support ticket reply submission',
                'subject' => 'New reply submitted for ticket',
                'body' => '<p>We have received reply for support ticket on website.</p><p>Please click on below link for more details</p><p>&nbsp;</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Support ticket submission',
                'subject' => 'New ticket submission',
                'body' => '<p>We have received new support ticket #[ticket_number] on website.</p><p>Please click on below link for more details.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Email update notification',
                'subject' => 'Security Alert',
                'body' => '<p>We have received email update request for your account.<br />You&#39;re getting this email to make sure it was you.<br />You can contact site administrator in case any security issue found.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Client welcome email',
                'subject' => 'Warm Welcome to Money App!!',
                'body' => '<h1>Welcome to Money App - Client Portal</h1><p>If you have any query, please feel free to get in touch with one of our executive on 02 8859 2577 during 9:30 AM to 5:30 PM from Monday to Friday.&nbsp;</p><p>You can login using below details.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Referral Partner Welcome email',
                'subject' => 'Warm Welcome to Money App!!',
                'body' => '<h1>Welcome to Money App</h1><p>You will have to verify yourself by submitting documents after logging in. Once you are verified by our team, you will get full access of our services.</p><p>If you have any query, please feel free to get in touch with one of our executive on 02 8859 2577 during 9:30 AM to 5:30 PM from Monday to Friday.&nbsp;</p><p>You can login using below details.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Change Password OTP',
                'subject' => 'Change Password OTP',
                'body' => '<p>Thank you for choosing a MoneyApp.</p><p>Please find below OTP for change/forgot password</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Send Money OTP',
                'subject' => 'Send Money OTP',
                'body' => '<p>Thank you for choosing a MoneyApp.</p><p>Please find below OTP for verification payment</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Email Verification OTP',
                'subject' => 'Email Verification OTP',
                'body' => '<p>Thank you for choosing a MoneyApp.</p><p>Please find below OTP for verify your email Address.</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Payment detail email to admin',
                'subject' => 'Payment detail email to admin',
                'body' => '<p>[textAboveLink]</p><p>You are receiving this email because you have received amount in monoova account.</p><p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Payment detail email to beneficiary',
                'subject' => 'Payment detail email to beneficiary',
                'body' => '<p>[textAboveLink]</p><p>You are receiving this email because you have received payment.</p><p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'NPP Return email to admin',
                'subject' => 'NPP Return email to admin',
                'body' => '<p>[textAboveLink]</p><p>We have received below Monoova Account NPP Return Submission.</p><p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Direct entry dishonour email to admin',
                'subject' => 'Direct entry dishonour email to admin',
                'body' => '<p>[textAboveLink]</p><p>We have received below Monoova Account Payment Direct Entry Dishonour submission.</p><p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Payment detail email to user',
                'subject' => 'Payment detail email to user',
                'body' => '<p>[textAboveLink]</p><p>You are receiving this email because you have sent payment to your beneficiary.</p><p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Payment status update email to user',
                'subject' => 'Payment status update',
                'body' => '<p>[textAboveLink]</p>\r\n<p>You are receiving this email because transaction status updated.</p>\r\n<p>[/textAboveLink]</p>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];


        DB::table('external_email_templates')->insert($createMultipleEmailTemplate);
    }
}
