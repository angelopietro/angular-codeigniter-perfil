import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PageNotFoundComponent } from './componentes/page-not-found/page-not-found.component';
import { HomeComponent } from './componentes/home/home.component';

const routes: Routes = [
  {
    path: 'home',
    children: [
      {
        path: '',
        loadChildren: () => import('./componentes/home/home.module').then(modulo => modulo.HomeModule),
        component: HomeComponent
      }
    ]
  },
  {
    path: 'dashboard',
    children: [
      {
        path: '',
        loadChildren: () => import('./componentes/dashboard/dashboard.module').then(modulo => modulo.DashboardModule)
      }
    ]
  },
  { path: '', pathMatch: 'full', redirectTo: 'home' },
  { path: '**', component: PageNotFoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
