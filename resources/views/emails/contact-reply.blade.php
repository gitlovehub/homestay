<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $replySubject }}</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f1f5f9;
    font-family: Arial, Helvetica, sans-serif;
    color: #0f172a;
">
    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="background-color: #f1f5f9;"
    >
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table
                    role="presentation"
                    width="100%"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="
                        max-width: 640px;
                        overflow: hidden;
                        border: 1px solid #e2e8f0;
                        border-radius: 16px;
                        background-color: #ffffff;
                    "
                >
                    {{-- Header --}}
                    <tr>
                        <td style="
                            padding: 24px 28px;
                            background-color: #2563eb;
                            color: #ffffff;
                        ">
                            <table
                                role="presentation"
                                width="100%"
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                            >
                                <tr>
                                    <td>
                                        <div style="
                                            font-size: 22px;
                                            font-weight: 700;
                                        ">
                                            HomeStayGo
                                        </div>

                                        <div style="
                                            margin-top: 6px;
                                            font-size: 13px;
                                            color: #dbeafe;
                                        ">
                                            Trung tâm hỗ trợ khách hàng
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Nội dung --}}
                    <tr>
                        <td style="padding: 28px;">
                            <p style="
                                margin: 0;
                                font-size: 16px;
                                line-height: 1.7;
                            ">
                                Xin chào
                                <strong>{{ $contactMessage->name }}</strong>,
                            </p>

                            <p style="
                                margin: 16px 0 0;
                                font-size: 15px;
                                line-height: 1.7;
                                color: #475569;
                            ">
                                HomeStayGo đã tiếp nhận và phản hồi yêu cầu hỗ trợ
                                của bạn.
                            </p>

                            {{-- Nội dung phản hồi --}}
                            <div style="
                                margin-top: 24px;
                                padding: 20px;
                                border: 1px solid #bfdbfe;
                                border-radius: 12px;
                                background-color: #eff6ff;
                            ">
                                <div style="
                                    margin-bottom: 10px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 0.05em;
                                    color: #2563eb;
                                ">
                                    Phản hồi từ HomeStayGo
                                </div>

                                <div style="
                                    font-size: 15px;
                                    line-height: 1.8;
                                    color: #1e293b;
                                ">{!! nl2br(e($replyMessage)) !!}</div>
                            </div>

                            {{-- Yêu cầu ban đầu --}}
                            <div style="
                                margin-top: 24px;
                                padding: 18px;
                                border: 1px solid #e2e8f0;
                                border-radius: 12px;
                                background-color: #f8fafc;
                            ">
                                <div style="
                                    font-size: 12px;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                    letter-spacing: 0.05em;
                                    color: #64748b;
                                ">
                                    Yêu cầu ban đầu của bạn
                                </div>

                                <div style="
                                    margin-top: 12px;
                                    font-size: 14px;
                                    line-height: 1.7;
                                    color: #334155;
                                ">
                                    <strong>Chủ đề:</strong>
                                    {{ $contactMessage->subject }}
                                </div>

                                <div style="
                                    margin-top: 8px;
                                    font-size: 14px;
                                    line-height: 1.7;
                                    color: #64748b;
                                ">{!! nl2br(e($contactMessage->message)) !!}</div>
                            </div>

                            <p style="
                                margin: 24px 0 0;
                                font-size: 14px;
                                line-height: 1.7;
                                color: #64748b;
                            ">
                                Cảm ơn bạn đã sử dụng HomeStayGo. Đội ngũ hỗ trợ
                                luôn sẵn sàng giúp đỡ khi bạn gặp vấn đề.
                            </p>

                            <p style="
                                margin: 20px 0 0;
                                font-size: 14px;
                                line-height: 1.7;
                            ">
                                Trân trọng,<br>
                                <strong>Đội ngũ HomeStayGo</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="
                            padding: 20px 28px;
                            border-top: 1px solid #e2e8f0;
                            background-color: #f8fafc;
                            text-align: center;
                            font-size: 12px;
                            line-height: 1.6;
                            color: #94a3b8;
                        ">
                            Đây là email phản hồi tự động từ hệ thống HomeStayGo.
                            Vui lòng không cung cấp mật khẩu hoặc thông tin thanh
                            toán nhạy cảm qua email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>