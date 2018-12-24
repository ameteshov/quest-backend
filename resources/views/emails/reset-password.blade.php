Your password were reset.

url: {{ env('FRONTEND_URL') . '/confirm?token=' . $data['reset_token'] }}
