<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Stamped - {{ $feeType }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #6b7280;
            line-height: 1.7;
        }
        
        .invoice-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #059669;
        }
        
        .invoice-details h3 {
            color: #059669;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #374151;
        }
        
        .detail-value {
            color: #6b7280;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #10b981;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
            margin: 0 10px 10px 0;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
        }
        
        .download-button {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
            margin: 0 10px 10px 0;
        }
        
        .download-button:hover {
            transform: translateY(-2px);
        }
        
        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .footer .logo {
            font-size: 20px;
            font-weight: 700;
            color: #059669;
            margin-bottom: 10px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .success-icon {
                width: 60px;
                height: 60px;
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">✓</div>
            <h1>Payment Verified!</h1>
            <p>Your {{ $feeType }} invoice has been successfully stamped</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $studentName }},
            </div>
            
            <div class="message">
                Great news! Your {{ $feeType }} payment has been verified and your invoice has been officially stamped by our administrative team. Your payment is now fully processed and recognized.
            </div>
            
            <!-- Invoice Details -->
            <div class="invoice-details">
                <h3>Invoice Details</h3>
                <div class="detail-row">
                    <span class="detail-label">RRR Number:</span>
                    <span class="detail-value">{{ $invoice->rrr }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fee Type:</span>
                    <span class="detail-value">{{ $feeType }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value"><span class="status-badge">Stamped</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Stamped Date:</span>
                    <span class="detail-value">{{ $invoice->stamped_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            <div class="message">
                Your stamped invoice is attached to this email. Please keep it for your records as proof of payment verification.
            </div>
            
            <!-- Call to Action -->
            <div class="cta-section">
                <div class="button-group">
                    <a href="{{ url('/dashboard') }}" class="cta-button">
                        View Dashboard
                    </a>
                    @if($invoice->stamped_file)
                        <a href="{{ url('/download/stamped-invoice/' . $invoice->id) }}" class="download-button">
                            📄 Download Stamped Receipt
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="message">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="logo">{{ config('app.name') }}</div>
            <p>Digital Fee Verification System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
