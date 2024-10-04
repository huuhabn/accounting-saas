<?php

return [
    'fields' => [
        'code' => 'Mã số',
        'current_password' => 'Mật khẩu hiện tại',
        'email' => 'E-mail',
        'name' => 'Tên',
        'password' => 'Mật khẩu',
        'recovery_code' => 'Mã khôi phục',
    ],
    'buttons' => [
        'add' => 'Thêm vào',
        'cancel' => 'Hủy bỏ',
        'close' => 'Đóng',
        'connect' => 'Kết nối',
        'confirm' => 'Xác nhận',
        'create' => 'Tạo nên',
        'create_token' => 'Tạo mã thông báo',
        'delete' => 'Xóa bỏ',
        'delete_account' => 'Xóa tài khoản',
        'delete_company' => 'Xóa công ty',
        'disable' => 'Vô hiệu hóa',
        'done' => 'Xong.',
        'edit' => 'Biên tập',
        'email_password_reset_link' => 'Liên kết đặt lại mật khẩu email',
        'enable' => 'Cho phép',
        'leave' => 'Rời khỏi',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'logout_browser_sessions' => 'Đăng xuất các phiên trình duyệt khác',
        'new_photo' => 'Ảnh mới',
        'permissions' => 'Quyền',
        'register' => 'Đăng ký',
        'regenerate_recovery_codes' => 'Tạo lại mã khôi phục',
        'remember_me' => 'nhớ tôi',
        'remove' => 'Di dời',
        'remove_connected_account' => 'Xóa tài khoản đã kết nối',
        'remove_photo' => 'Xóa ảnh',
        'reset_password' => 'Đặt lại mật khẩu',
        'resend_verification_email' => 'Gửi lại email xác minh',
        'revoke' => 'Thu hồi',
        'save' => 'Cứu',
        'show_recovery_codes' => 'Hiển thị mã khôi phục',
        'use_authentication_code' => 'Sử dụng mã xác thực',
        'use_avatar_as_profile_photo' => 'Sử dụng Hình đại diện',
        'use_recovery_code' => 'Sử dụng mã khôi phục',
    ],
    'labels' => [
        'company_name' => 'Tên công ty',
        'company_owner' => 'Chủ sở hữu công ty',
        'connected' => 'Đã kết nối',
        'created_at' => 'Được tạo vào lúc',
        'last_active' => 'Hoạt động lần cuối',
        'last_used' => 'Lần sử dụng cuối cùng',
        'last_used_at' => 'Được sử dụng lần cuối vào lúc',
        'new_password' => 'Mật khẩu mới',
        'not_connected' => 'Không được kết nối.',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'permissions' => 'Quyền',
        'photo' => 'Ảnh',
        'role' => 'Vai trò',
        'setup_key' => 'Khóa cài đặt',
        'this_device' => 'Thiết bị này',
        'token_name' => 'Tên mã thông báo',
        'unknown' => 'Không xác định',
        'updated_at' => 'Cập nhật lúc',
    ],
    'links' => [
        'already_registered' => 'Đã đăng ký chưa?',
        'edit_profile' => 'Chỉnh sửa hồ sơ',
        'forgot_your_password' => 'Bạn quên mật khẩu?',
        'privacy_policy' => 'Chính sách bảo mật',
        'register_an_account' => 'Đăng ký tài khoản',
        'terms_of_service' => 'Điều khoản dịch vụ',
    ],
    'errors' => [
        'cannot_leave_company' => 'Bạn không được rời bỏ công ty mà bạn đã tạo ra.',
        'company_deletion' => 'Bạn không được xóa công ty cá nhân của mình.',
        'email_already_associated' => 'Một tài khoản có địa chỉ email đó đã tồn tại. Vui lòng đăng nhập để kết nối tài khoản :Provider của bạn.',
        'email_not_found' => 'Chúng tôi không thể tìm thấy người dùng đã đăng ký với địa chỉ email này.',
        'employee_already_belongs_to_company' => 'Nhân viên này đã thuộc về công ty.',
        'employee_already_invited' => 'Nhân viên này đã được mời đến công ty.',
        'generic_error' => 'Đã xảy ra lỗi khi xử lý yêu cầu của bạn.',
        'invalid_password' => 'Mật khẩu bạn nhập không hợp lệ.',
        'no_email_with_account' => 'Không có địa chỉ email nào được liên kết với tài khoản :Provider này. Vui lòng thử một tài khoản khác.',
        'password_does_not_match' => 'Mật khẩu được cung cấp không khớp với mật khẩu hiện tại của bạn.',
        'already_associated_account' => 'Một tài khoản có: Nhà cung cấp đăng nhập đã tồn tại, vui lòng đăng nhập.',
        'already_connected' => 'Một tài khoản có địa chỉ email đó đã tồn tại. Vui lòng đăng nhập để kết nối tài khoản :Provider của bạn.',
        'signin_not_found' => 'Không tìm thấy tài khoản có :Đăng nhập nhà cung cấp. Vui lòng đăng ký hoặc thử phương thức đăng nhập khác.',
        'user_belongs_to_company' => 'Người dùng này đã thuộc về công ty.',
        'valid_role' => ':attribute phải là một vai trò hợp lệ.',
        'terms' => 'Điều khoản dịch vụ và Chính sách quyền riêng tư',
    ],
    'descriptions' => [
        'token_created_state' => 'Được tạo :time_ago bởi :user_name.',
        'token_last_used_state' => 'Lần sử dụng cuối cùng: time_ago',
        'token_never_used' => 'Chưa bao giờ sử dụng',
        'token_updated_state' => 'Đã cập nhật: time_ago',
    ],
    'banner' => [
        'company_invitation_accepted' => 'Tuyệt vời! Bạn đã chấp nhận lời mời tham gia **:company**.',
    ],
    'notifications' => [
        'token_created' => [
            'title' => 'Mã thông báo truy cập cá nhân đã được tạo',
            'body' => 'Mã thông báo truy cập cá nhân mới đã được tạo với tên **:name**.',
        ],
        'token_updated' => [
            'title' => 'Đã cập nhật mã thông báo truy cập cá nhân',
            'body' => 'Mã thông báo truy cập cá nhân đã được cập nhật thành công.',
        ],
        'browser_sessions_terminated' => [
            'title' => 'Phiên trình duyệt đã kết thúc',
            'body' => 'Tài khoản của bạn đã bị đăng xuất khỏi các phiên trình duyệt khác vì mục đích bảo mật.',
        ],
        'company_created' => [
            'title' => 'Công ty được tạo ra',
            'body' => 'Một công ty mới đã được thành lập với tên **:name**.',
        ],
        'company_deleted' => [
            'title' => 'Công ty đã bị xóa',
            'body' => 'Công ty **:name** đã bị xóa.',
        ],
        'company_invitation_sent' => [
            'title' => 'Đã gửi lời mời',
            'body' => 'Lời mời đã được gửi tới **:email** để gia nhập công ty của bạn.',
        ],
        'company_name_updated' => [
            'title' => 'Công ty đã cập nhật',
            'body' => 'Tên công ty của bạn đã được cập nhật thành **:name**.',
        ],
        'connected_account_removed' => [
            'title' => 'Đã xóa tài khoản được kết nối',
            'body' => 'Tài khoản được kết nối đã được xóa thành công.',
        ],
        'password_set' => [
            'title' => 'Đã đặt mật khẩu',
            'body' => 'Tài khoản của bạn hiện đã được bảo vệ bằng mật khẩu. Trang này sẽ tự động làm mới trong giây lát để cập nhật cài đặt của bạn.',
        ],
        'password_updated' => [
            'title' => 'Đã cập nhật mật khẩu',
            'body' => 'Mật khẩu của bạn đã được cập nhật thành công.',
        ],
        'profile_information_updated' => [
            'title' => 'Thông tin hồ sơ được cập nhật',
            'body' => 'Thông tin hồ sơ của bạn đã được cập nhật thành công.',
        ],
        'already_associated' => [
            'title' => 'Ối!',
            'body' => 'Tài khoản đăng nhập :Nhà cung cấp này đã được liên kết với người dùng của bạn.',
        ],
        'belongs_to_other_user' => [
            'title' => 'Ối!',
            'body' => 'Tài khoản đăng nhập của nhà cung cấp này đã được liên kết với một người dùng khác. Vui lòng thử một tài khoản khác.',
        ],
        'successfully_connected' => [
            'title' => 'Thành công!',
            'body' => 'Bạn đã kết nối thành công :Provider với tài khoản của mình.',
        ],
        'verification_link_sent' => [
            'title' => 'Đã gửi liên kết xác minh',
            'body' => 'Một liên kết xác minh mới đã được gửi đến địa chỉ email được cung cấp.',
        ],
    ],
    'navigation' => [
        'headers' => [
            'manage_company' => 'Quản lý công ty',
            'switch_companies' => 'Chuyển đổi công ty',
        ],
        'links' => [
            'tokens' => 'Mã thông báo truy cập cá nhân',
            'company_settings' => 'Cài đặt công ty',
            'create_company' => 'Tạo công ty',
        ],
    ],
    'pages' => [
        'titles' => [
            'tokens' => 'Mã thông báo truy cập cá nhân',
            'create_company' => 'Tạo công ty',
            'company_settings' => 'Cài đặt công ty',
            'profile' => 'Hồ sơ',
        ],
    ],
    'grid_section_titles' => [
        'add_company_employee' => 'Thêm nhân viên công ty',
        'browser_sessions' => 'Phiên trình duyệt',
        'company_name' => 'Tên công ty',
        'create_token' => 'Tạo mã thông báo truy cập cá nhân',
        'create_company' => 'Tạo công ty',
        'delete_account' => 'Xóa tài khoản',
        'profile_information' => 'Thông tin hồ sơ',
        'set_password' => 'Đặt mật khẩu',
        'two_factor_authentication' => 'Xác thực hai yếu tố',
        'update_password' => 'Cập nhật mật khẩu',
    ],
    'grid_section_descriptions' => [
        'add_company_employee' => 'Thêm nhân viên công ty mới vào công ty của bạn, cho phép họ cộng tác với bạn.',
        'browser_sessions' => 'Quản lý và đăng xuất các phiên hoạt động của bạn trên các trình duyệt và thiết bị khác.',
        'company_name' => 'Tên công ty và thông tin chủ sở hữu.',
        'create_token' => 'Mã thông báo truy cập cá nhân cho phép các dịch vụ của bên thứ ba thay mặt bạn xác thực với ứng dụng của chúng tôi.',
        'create_company' => 'Tạo một công ty mới để cộng tác với những người khác trong các dự án.',
        'delete_account' => 'Xóa vĩnh viễn tài khoản của bạn.',
        'profile_information' => 'Cập nhật thông tin hồ sơ và địa chỉ email của tài khoản của bạn.',
        'set_password' => 'Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để giữ an toàn.',
        'two_factor_authentication' => 'Thêm bảo mật bổ sung cho tài khoản của bạn bằng xác thực hai yếu tố.',
        'update_password' => 'Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để giữ an toàn.',
    ],
    'action_section_titles' => [
        'company_employees' => 'Nhân viên công ty',
        'connected_accounts' => 'Tài khoản được kết nối',
        'delete_company' => 'Xóa công ty',
        'pending_company_invitations' => 'Lời mời của công ty đang chờ xử lý',
    ],
    'action_section_descriptions' => [
        'company_employees' => 'Tất cả những người là một phần của công ty này.',
        'connected_accounts' => 'Quản lý và xóa các tài khoản được kết nối của bạn.',
        'delete_company' => 'Xóa vĩnh viễn công ty này.',
        'pending_company_invitations' => 'Những người này đã được mời đến công ty của bạn và đã được gửi email mời. Họ có thể gia nhập công ty bằng cách chấp nhận lời mời qua email.',
    ],
    'modal_titles' => [
        'token' => 'Mã thông báo truy cập cá nhân',
        'token_permissions' => 'Quyền truy cập mã thông báo cá nhân',
        'confirm_password' => 'Xác nhận mật khẩu',
        'delete_token' => 'Xóa mã thông báo truy cập cá nhân',
        'delete_account' => 'Xóa tài khoản',
        'delete_company' => 'Xóa công ty',
        'leave_company' => 'Rời khỏi công ty',
        'logout_browser_sessions' => 'Đăng xuất các phiên trình duyệt khác',
        'manage_role' => 'Quản lý vai trò',
        'remove_company_employee' => 'Xóa nhân viên công ty',
        'remove_connected_account' => 'Xóa tài khoản đã kết nối',
        'revoke_tokens' => 'Thu hồi mã thông báo',
    ],
    'modal_descriptions' => [
        'copy_token' => 'Vui lòng sao chép Mã truy cập cá nhân mới của bạn. Để bảo mật cho bạn, nó sẽ không được hiển thị lại.',
        'confirm_password' => 'Để bảo mật, vui lòng xác nhận mật khẩu của bạn để tiếp tục.',
        'delete_account' => 'Vui lòng nhập mật khẩu của bạn để xác nhận bạn muốn xóa tài khoản của mình.',
        'delete_token' => 'Bạn có chắc chắn muốn xóa Mã thông báo truy cập cá nhân này không?',
        'delete_company' => 'Bạn có chắc chắn muốn xóa công ty này không?',
        'leave_company' => 'Bạn có chắc chắn muốn rời khỏi công ty này không?',
        'logout_browser_sessions' => 'Vui lòng nhập mật khẩu của bạn để xác nhận rằng bạn muốn đăng xuất khỏi các phiên trình duyệt khác của mình.',
        'remove_company_employee' => 'Bạn có chắc chắn muốn xóa người này khỏi công ty không?',
        'remove_connected_account' => 'Vui lòng xác nhận việc xóa tài khoản này của bạn - hành động này không thể hoàn tác.',
        'revoke_tokens' => 'Vui lòng nhập mật khẩu của bạn để xác nhận.',
    ],
    'headings' => [
        'auth' => [
            'confirm_password' => 'Đây là khu vực an toàn của ứng dụng. Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục.',
            'forgot_password' => 'Bạn quên mật khẩu?',
            'login' => 'Đăng nhập vào tài khoản của bạn',
            'register' => 'Đăng ký tài khoản',
            'two_factor_challenge' => [
                'authentication_code' => 'Vui lòng xác nhận quyền truy cập vào tài khoản của bạn bằng cách nhập mã xác thực được cung cấp bởi ứng dụng xác thực của bạn.',
                'emergency_recovery_code' => 'Vui lòng xác nhận quyền truy cập vào tài khoản của bạn bằng cách nhập một trong các mã khôi phục khẩn cấp của bạn.',
            ],
            'verify_email' => [
                'verification_link_not_sent' => 'Trước khi tiếp tục, bạn có thể xác minh địa chỉ email của mình bằng cách nhấp vào liên kết mà chúng tôi vừa gửi qua email cho bạn không? Nếu bạn không nhận được email, chúng tôi sẽ sẵn lòng gửi cho bạn một email khác.',
                'verification_link_sent' => 'Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp trong cài đặt hồ sơ của mình.',
            ],
        ],
        'profile' => [
            'connected_accounts' => [
                'has_connected_accounts' => 'Tài khoản được kết nối của bạn.',
                'no_connected_accounts' => 'Bạn không có tài khoản nào được kết nối.',
            ],
            'two_factor_authentication' => [
                'enabled' => 'Bạn đã kích hoạt xác thực hai yếu tố!',
                'finish_enabling' => 'Hoàn tất việc kích hoạt xác thực hai yếu tố.',
                'not_enabled' => 'Bạn chưa kích hoạt xác thực hai yếu tố.',
            ],
            'update_profile_information' => [
                'verification_link_not_sent' => 'Trước khi email của bạn có thể được cập nhật, bạn phải xác minh địa chỉ email hiện tại của mình.',
                'verification_link_sent' => 'Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.',
            ],
        ],
        'tokens' => [
            'token_manager' => [
                'manage_tokens' => 'Quản lý mã thông báo truy cập cá nhân',
            ],
        ],
        'companies' => [
            'company_employee_manager' => [
                'manage_employees' => 'Quản lý nhân viên',
                'pending_invitations' => 'Lời mời đang chờ xử lý',
            ],
        ],
    ],
    'subheadings' => [
        'auth' => [
            'forgot_password' => 'Chỉ cần cho chúng tôi biết địa chỉ email của bạn và chúng tôi sẽ gửi cho bạn liên kết đặt lại mật khẩu qua email để cho phép bạn chọn địa chỉ mới.',
            'login' => 'Hoặc',
            'register' => 'Tôi đồng ý với :terms_of_service và :privacy_policy',
        ],
        'profile' => [
            'two_factor_authentication' => [
                'enabled' => 'Xác thực hai yếu tố hiện đã được bật. Quét mã QR sau bằng ứng dụng xác thực trên điện thoại của bạn hoặc nhập khóa thiết lập.',
                'finish_enabling' => 'Để hoàn tất việc kích hoạt xác thực hai yếu tố, hãy quét mã QR sau bằng ứng dụng xác thực trên điện thoại của bạn hoặc nhập khóa thiết lập và cung cấp mã OTP đã tạo.',
                'store_codes' => 'Lưu trữ các mã khôi phục này trong trình quản lý mật khẩu an toàn. Chúng có thể được sử dụng để khôi phục quyền truy cập vào tài khoản của bạn nếu thiết bị xác thực hai yếu tố của bạn bị mất.',
                'summary' => 'Khi xác thực hai yếu tố được bật, bạn sẽ được nhắc nhập mã thông báo ngẫu nhiên, an toàn trong quá trình xác thực. Bạn có thể truy xuất mã thông báo này từ ứng dụng Google Authenticator trên điện thoại của mình.',
            ],
            'connected_accounts' => 'Bạn có thể tự do kết nối bất kỳ tài khoản xã hội nào với hồ sơ của mình và có thể xóa mọi tài khoản được kết nối bất kỳ lúc nào. Nếu bạn cảm thấy bất kỳ tài khoản nào được kết nối của mình đã bị xâm phạm, bạn nên ngắt kết nối chúng ngay lập tức và thay đổi mật khẩu của mình.',
            'delete_user' => 'Sau khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của tài khoản đó sẽ bị xóa vĩnh viễn. Trước khi xóa tài khoản của bạn, vui lòng tải xuống mọi dữ liệu hoặc thông tin mà bạn muốn giữ lại.',
            'logout_other_browser_sessions' => 'Nếu cần, bạn có thể đăng xuất khỏi tất cả các phiên trình duyệt khác trên tất cả các thiết bị của mình. Một số phiên gần đây của bạn được liệt kê dưới đây; tuy nhiên, danh sách này có thể không đầy đủ. Nếu bạn cảm thấy tài khoản của mình đã bị xâm phạm, bạn cũng nên cập nhật mật khẩu của mình.',
        ],
        'companies' => [
            'company_employee_manager' => 'Vui lòng cung cấp địa chỉ email của người bạn muốn thêm vào công ty này.',
            'delete_company' => 'Sau khi một công ty bị xóa, tất cả tài nguyên và dữ liệu của công ty đó sẽ bị xóa vĩnh viễn. Trước khi xóa công ty này, vui lòng tải xuống bất kỳ dữ liệu hoặc thông tin nào về công ty này mà bạn muốn giữ lại.',
        ],
    ]
    ];
