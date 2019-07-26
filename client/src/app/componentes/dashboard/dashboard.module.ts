import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PerfilComponent } from './perfil/perfil.component';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { MaterialModule } from '../../shared/models'; 
import { NgxMaskModule } from 'ngx-mask';
import { NgxLoadingModule } from 'ngx-loading';
import { PasswordStrengthBarModule } from 'ng2-password-strength-bar';
import { DashboardRoutingModule } from './dashboard-routing.module';
import { DashboardComponent } from './dashboard.component';

@NgModule({
  declarations: [PerfilComponent, DashboardComponent],
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    DashboardRoutingModule,
    MaterialModule, 
    NgxMaskModule.forRoot(),
    NgxLoadingModule.forRoot({
      backdropBackgroundColour: 'rgba(0,0,0,0)',
      backdropBorderRadius: '4px',
      primaryColour: '#1574b3',
      secondaryColour: '#1574b3',
      tertiaryColour: '#1574b3'
    }),
    PasswordStrengthBarModule,
  ]
})
export class DashboardModule { }
