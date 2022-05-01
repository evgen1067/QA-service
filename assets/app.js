// импорт наших стилей
import './styles/app.css';

// импорт стилей bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.js';
import 'jquery/dist/jquery.min';
import '@fortawesome/fontawesome-free/css/all.css';

import './bootstrap';

import $ from 'jquery';

$('#copyApiToken').click(function () {
    let copyText = document.getElementById('copyApiToken').value;
    navigator.clipboard.writeText(copyText);
    alert("Скопировано в буфер обмена: " + copyText);
});
