import './style';
import register from 'ShopUi/app/registry';
export default register('rating-selector', () => import(/* webpackMode: "lazy" */'./rating-selector'));
