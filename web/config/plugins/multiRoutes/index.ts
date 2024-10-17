import {IApi} from '@umijs/max';

export default (api: IApi) => {
  api.modifyRoutes((memo) => {
    Object.keys(memo).forEach((id) => {
      const route = memo[id];
      if(route.path.includes('backend')){
        route.path = route.path.replace(new RegExp('backend/', 'g'), '');
      }
      if(route.path.includes('frontend')){
        route.path = route.path.replace(new RegExp('frontend/', 'g'), '');
      }
      if(route.path.includes('public')){
        route.path = route.path.replace(new RegExp('public/', 'g'), '');
      }
    });
    return memo;
  })
}
