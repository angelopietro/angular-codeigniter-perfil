import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { MaterialModule } from './shared/models';      
import { Angular2PromiseButtonModule } from 'angular2-promise-buttons/dist';
import { SimpleNotificationsModule } from 'angular2-notifications';
import { AuthGuard } from './core/guards/auth.guard';
import { AuthService } from './core/services/auth.service';
import { JwtModule } from '@auth0/angular-jwt';
import { JwtInterceptor } from "./core/interceptor/jwt.interceptor";
import { HTTP_INTERCEPTORS } from '@angular/common/http';

import { DashboardModule } from './componentes/dashboard/dashboard.module';
import { RecuperarSenhaComponent } from './componentes/home/recuperar-senha/recuperar-senha.component';
import { PageNotFoundComponent } from './componentes/page-not-found/page-not-found.component';
import { HomeModule } from './componentes/home/home.module';

@NgModule({
  declarations: [
    AppComponent,
    RecuperarSenhaComponent,
    PageNotFoundComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    NgbModule,
    MaterialModule,
    HomeModule,
    DashboardModule,
    Angular2PromiseButtonModule.forRoot(),
    SimpleNotificationsModule.forRoot(
      {
        timeOut: 3000,
        preventDuplicates: true,
        preventLastDuplicates: true,
        showProgressBar: true,
        pauseOnHover: true,
        clickToClose: true,
        position: ["top", "right"],
        theClass: ".notify"
      }
    ),
    JwtModule.forRoot(
      {
        config: {
          tokenGetter: function tokenGetter() {
            return localStorage.getItem('access_token');
          },
          whitelistedDomains: ['localhost:3000'],
          blacklistedRoutes: ['http://localhost:3000/painel-perfil/backend/php/api/auth']
        }
      }
    )
  ],
  providers: [AuthService, AuthGuard, {
    provide: HTTP_INTERCEPTORS,
    useClass: JwtInterceptor,
    multi: true
  }],
  bootstrap: [AppComponent]
})
export class AppModule { }
