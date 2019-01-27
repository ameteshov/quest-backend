@extends('emails.template')
@section('content')
    <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;">
        <div style="mso-line-height-rule: exactly;line-height: 20px;font-size: 1px;">&nbsp;</div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;">
            <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #565656;font-size: 30px;line-height: 38px;text-align: center;"><strong>@lang('form.subject')</strong></h1><p style="Margin-top: 20px;Margin-bottom: 0;">&nbsp;<br />
                {{$name}}, добрый день!
            <p style="Margin-top: 20px;Margin-bottom: 20px;">
                Мы получили и уже передали руководителю вашу анкету на вакансию. Мы приглашаем вас пройти заключительный этап перед собеседованием.
                Это будет тест, заполнение которого займет у вас от 20 до 40 минут и сэкономит нам с вами порядка часа на собеседовании.
                Пройти данный тест вы можете как с компьютера, так и с мобильного устройства. В вашем распоряжении 24 часа с момента получения данного письма.
                Тест содержит простые жизненные ситуации и ваше отношение к ним.
                По каждому вопросу вы сможете выбрать один из трех вариантов ответа.
                В тесте нет правильных или неправильных ответов, поэтому отвечайте на вопросы максимально честно и откровенно.
            </p>
        </div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;line-height: 10px;font-size: 1px;">&nbsp;</div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div class="btn btn--flat btn--large" style="Margin-bottom: 20px;text-align: center;">
            <![if !mso]><a style="border-radius: 4px;display: inline-block;font-size: 14px;font-weight: bold;line-height: 24px;padding: 12px 24px;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;color: #ffffff !important;background-color: #80bf2e;font-family: Ubuntu, sans-serif;"
                           href="{{ env('FRONTEND_URL') . "/forms/{$hash}" }}">ПРОЙТИ ТЕСТИРОВАНИЕ</a><![endif]>
            <!--[if mso]><p style="line-height:0;margin:0;">&nbsp;</p><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="{{ env('FRONTEND_URL') . "/forms/{$hash}" }}" style="width:212px" arcsize="9%" fillcolor="#80BF2E" stroke="f"><v:textbox style="mso-fit-shape-to-text:t" inset="0px,11px,0px,11px"><center style="font-size:14px;line-height:24px;color:#FFFFFF;font-family:Ubuntu,sans-serif;font-weight:bold;mso-line-height-rule:exactly;mso-text-raise:4px">COMPLETE THE<br>
                SURVEY</center></v:textbox></v:roundrect><![endif]--></div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;">
        <div style="mso-line-height-rule: exactly;line-height: 10px;font-size: 1px;">&nbsp;</div>
    </div>

    <div style="Margin-left: 20px;Margin-right: 20px;Margin-bottom: 24px;">
        <div style="mso-line-height-rule: exactly;line-height: 5px;font-size: 1px;">&nbsp;</div>
    </div>
@endsection
