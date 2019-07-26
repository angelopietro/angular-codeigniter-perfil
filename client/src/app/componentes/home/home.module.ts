import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MaterialModule } from '../../shared/models';
import { NgxMaskModule } from 'ngx-mask';
import { NgxLoadingModule, ngxLoadingAnimationTypes } from 'ngx-loading';
import { PasswordStrengthBarModule } from 'ng2-password-strength-bar';
import { HomeRoutingModule } from './home-routing.module';
import { LoginComponent } from './login/login.component';
import { CadastroComponent } from './cadastro/cadastro.component';
import { HomeComponent } from './home.component';
import { NgxImgModule } from 'ngx-img';
import { HttpClientModule } from '@angular/common/http';
import { Angular2PromiseButtonModule } from 'angular2-promise-buttons';


@NgModule({
  declarations: [HomeComponent, LoginComponent, CadastroComponent],
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    HomeRoutingModule,
    MaterialModule,
    NgxMaskModule.forRoot(),
    NgxLoadingModule.forRoot({
      backdropBackgroundColour: 'rgba(0,0,0,0.2)',
      backdropBorderRadius: '4px',
      primaryColour: '#1574b3',
      secondaryColour: '#1574b3',
      tertiaryColour: '#1574b3'
    }),
    PasswordStrengthBarModule,
    NgxImgModule.forRoot(),
    Angular2PromiseButtonModule.forRoot(),
  ]
})
export class HomeModule { }
