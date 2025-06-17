<?php
/**
 * Tạo một tooltip sử dụng thuần CSS thay vì JavaScript
 * 
 * @param string $text Nội dung hiển thị
 * @param string $tooltip Nội dung tooltip
 * @param string $position Vị trí tooltip (top, bottom, left, right)
 * @return string HTML với tooltip CSS
 */
function create_tooltip($text, $tooltip, $position = 'top') {
    $css_classes = "tooltip-element";
    
    // Add custom CSS styles if not already added
    static $css_added = false;
    if (!$css_added) {
        echo '<style>
        .tooltip-element {
            position: relative;
            display: inline-block;
        }
        
        .tooltip-element .tooltip-text {
            visibility: hidden;
            width: auto;
            min-width: 120px;
            max-width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 14px;
        }
        
        .tooltip-position-top {
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .tooltip-position-bottom {
            top: 125%;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .tooltip-position-left {
            right: 125%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .tooltip-position-right {
            left: 125%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .tooltip-element:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        </style>';
        $css_added = true;
    }
    
    // Generate the HTML for the tooltip
    $html = '<div class="' . $css_classes . '">';
    $html .= $text;
    $html .= '<span class="tooltip-text tooltip-position-' . $position . '">' . $tooltip . '</span>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Tạo icon social media với tooltip
 * 
 * @param string $platform Tên nền tảng (facebook, twitter, etc.)
 * @param string $url URL đến trang mạng xã hội
 * @param string $tooltip Nội dung tooltip
 * @return string HTML với tooltip
 */
function social_media_icon($platform, $url = '#', $tooltip = '') {
    $icon_class = '';
    
    // Đặt class icon dựa trên nền tảng
    switch (strtolower($platform)) {
        case 'facebook':
            $icon_class = 'fab fa-facebook-f';
            $tooltip = $tooltip ?: 'Facebook';
            break;
        case 'twitter':
            $icon_class = 'fab fa-twitter';
            $tooltip = $tooltip ?: 'Twitter';
            break;
        case 'instagram':
            $icon_class = 'fab fa-instagram';
            $tooltip = $tooltip ?: 'Instagram';
            break;
        case 'linkedin':
            $icon_class = 'fab fa-linkedin';
            $tooltip = $tooltip ?: 'LinkedIn';
            break;
        case 'google':
        case 'google-plus':
            $icon_class = 'fab fa-google-plus-g';
            $tooltip = $tooltip ?: 'Google+';
            break;
        default:
            $icon_class = 'fas fa-link';
            $tooltip = $tooltip ?: $platform;
    }
    
    // Tạo icon với tooltip
    $icon_html = '<a href="' . $url . '" class="social-icon"><i class="' . $icon_class . ' fa-2x"></i></a>';
    return create_tooltip($icon_html, $tooltip);
}
?> 