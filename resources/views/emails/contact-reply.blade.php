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
    background-color: #f1f5f9;
    font-family: Arial, Helvetica, sans-serif;
    color: #0f172a;
">
    <div style="padding: 32px 16px;">
        <div style="
            max-width: 640px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background-color: #ffffff;
        ">
            {{-- Header --}}
            <div style="
                padding: 28px 32px;
                background-color: #2563eb;
                color: #ffffff;
            ">
                <h1 style="
                    margin: 0;
                    font-size: 24px;
                    line-height: 1.4;
                ">
                    HomeStayGo
                </h1>

                <p style="
                    margin: 8px 0 0;
                    color: #dbeafe;
                    font-size: 14px;
                ">
                    Phản hồi yêu cầu hỗ trợ
                </p>
            </div>

            {{-- Nội dung --}}
            <div style="padding: 32px;">
                <p style="
                    margin: 0 0 20px;
                    font-size: 16px;
                    line-height: 1.7;
                ">
                    Xin chào
                    <strong>{{ $contact->name }}</strong>,
                </p>

                <p style="
                    margin: 0 0 20px;
                    font-size: 15px;
                    line-height: 1.7;
                    color: #475569;
                ">
                    HomeStayGo đã tiếp nhận yêu cầu hỗ trợ của bạn với chủ đề:
                </p>

                <div style="
                    margin-bottom: 24px;
                    padding: 16px 18px;
                    border-left: 4px solid #2563eb;
                    border-radius: 8px;
                    background-color: #eff6ff;
                ">
                    <strong>{{ $contact->subject }}</strong>
                </div>

                <div style="
                    font-size: 15px;
                    line-height: 1.8;
                    color: #334155;
                ">
                    {!! nl2br(e($replyMessage)) !!}
                </div>

                <div style="
                    margin-top: 32px;
                    padding-top: 24px;
                    border-top: 1px solid #e2e8f0;
                ">
                    <p style="
                        margin: 0;
                        font-size: 14px;
                        line-height: 1.7;
                        color: #64748b;
                    ">
                        Trân trọng,<br>
                        <strong style="color: #0f172a;">
                            Đội ngũ hỗ trợ HomeStayGo
                        </strong>
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div style="
                padding: 20px 32px;
                background-color: #f8fafc;
                text-align: center;
            ">
                <p style="
                    margin: 0;
                    font-size: 12px;
                    line-height: 1.6;
                    color: #94a3b8;
                ">
                    Email này được gửi từ hệ thống hỗ trợ của HomeStayGo.
                    Vui lòng không cung cấp mật khẩu hoặc thông tin thanh toán nhạy cảm.
                </p>
            </div>
        </div>
    </div>
</body>

</html>