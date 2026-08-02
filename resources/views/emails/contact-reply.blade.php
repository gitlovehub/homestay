<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $replySubject }}</title>
</head>
<body style="
    margin: 0;
    padding: 0;
    background-color: #f0f7ff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #0f172a;
">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0f7ff;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                    style="max-width: 620px; overflow: hidden; border-radius: 18px; background-color: #ffffff; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.08);">
                    
                    <!-- Top accent bar -->
                    <tr>
                        <td style="height: 5px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td style="padding: 28px 32px 20px; background-color: #ffffff; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #1e40af; letter-spacing: 0.5px;">
                                HomeStayGo
                            </div>
                            <div style="margin-top: 6px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; color: #60a5fa;">
                                Trung tâm hỗ trợ khách hàng
                            </div>
                            <div style="margin: 18px auto 0; width: 40px; height: 2px; background-color: #93c5fd;"></div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 8px 32px 32px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.7;">
                                Xin chào <strong>{{ $contactMessage->name }}</strong>,
                            </p>

                            <p style="margin: 14px 0 0; font-size: 15px; line-height: 1.75; color: #475569;">
                                HomeStayGo đã tiếp nhận và phản hồi yêu cầu hỗ trợ của bạn.
                            </p>

                            <!-- Reply box -->
                            <div style="margin-top: 26px; padding: 22px; border-radius: 12px; background-color: #eff6ff; border: 1px solid #bfdbfe;">
                                <div style="margin-bottom: 10px; font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #2563eb;">
                                    Phản hồi từ HomeStayGo
                                </div>
                                <div style="font-size: 15px; line-height: 1.8; color: #1e293b;">
                                    {!! nl2br(e($replyMessage)) !!}
                                </div>
                            </div>

                            <!-- Original request -->
                            <div style="margin-top: 20px; padding: 20px; border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <div style="font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #64748b;">
                                    Yêu cầu ban đầu của bạn
                                </div>
                                <div style="margin-top: 12px; font-size: 14px; line-height: 1.7; color: #334155;">
                                    <strong>Chủ đề:</strong> {{ $contactMessage->subject }}
                                </div>
                                <div style="margin-top: 8px; font-size: 14px; line-height: 1.7; color: #64748b;">
                                    {!! nl2br(e($contactMessage->message)) !!}
                                </div>
                            </div>

                            <p style="margin: 26px 0 0; font-size: 14px; line-height: 1.7; color: #64748b;">
                                Cảm ơn bạn đã sử dụng HomeStayGo. Đội ngũ hỗ trợ luôn sẵn sàng giúp đỡ khi bạn gặp vấn đề.
                            </p>

                            <p style="margin: 18px 0 0; font-size: 14px; line-height: 1.7;">
                                Trân trọng,<br>
                                <strong style="color: #1e40af;">Đội ngũ HomeStayGo</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 18px 32px; background-color: #f1f5f9; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; line-height: 1.6; color: #94a3b8;">
                            Đây là email phản hồi từ hệ thống HomeStayGo.<br>
                            Vui lòng không cung cấp mật khẩu hoặc thông tin thanh toán nhạy cảm qua email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>