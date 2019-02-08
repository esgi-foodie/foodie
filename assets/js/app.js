// CSS
import 'materialize-css/dist/css/materialize.min.css';
import 'materialize-css/sass/materialize.scss';
import '../css/app.css';

// JS
import 'materialize-css';
import 'jquery';
import feather from 'feather-icons';

feather.replace();
$('select').formSelect();
M.AutoInit();

// Images
const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|svg)$/);
imagesContext.keys().forEach(imagesContext);

