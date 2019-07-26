
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './login/login.component';
import { CadastroComponent } from './cadastro/cadastro.component';
import { HomeComponent } from './home.component';
import { PerfilComponent } from '../dashboard/perfil/perfil.component';

const homeRoutes: Routes = [
  { path: 'home', component: HomeComponent },
  { path: 'login', component: LoginComponent },
  { path: 'cadastro', component: CadastroComponent },
  {
    path: 'dashboard',
    children: [
      {
        path: '',
        loadChildren: () => import('../dashboard/dashboard.module').then(modulo => modulo.DashboardModule),
        component: HomeComponent
      }
    ]
  },
  { path: '', component: HomeComponent },
];

@NgModule({
  imports: [RouterModule.forChild(homeRoutes)],
  exports: [RouterModule]
})

export class HomeRoutingModule { }
