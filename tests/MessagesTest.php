<?php

namespace NunoDonato\AnthropicAPIPHP\Tests;

use Env\Dotenv;
use NunoDonato\AnthropicAPIPHP\Messages;
use NunoDonato\AnthropicAPIPHP\Tools;
use PHPUnit\Framework\TestCase;
use NunoDonato\AnthropicAPIPHP\Client;

class MessagesTest extends TestCase
{
    public function test_can_add_simple_text_message()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, 'Hello');
        $this->assertCount(1, $messages->messages());
    }

    public function test_can_add_object_text_message()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, [[
            'type' => 'text',
            'text' => 'Hello',
        ]]);
        $this->assertCount(1, $messages->messages());
    }

    public function test_can_add_image_message()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, [[
            'type' => 'image',
            'source' => [
                'type' => 'base64',
                'data' => "iVBORw0KGgoAAAANSUhEUgAAASwAAADICAYAAABS39xVAAABfmlDQ1BJQ0MgcHJvZmlsZQAAKJF9kT1IQlEYhp97rYzQgnKIaLhDNSVBRTSWBRIYiBWoNXR/0gTvTe5VWhyDVqGhnyWroaW51obWIAj6gWhuaCpqibhxVFAi+5bz8J7v/Tjfe0AuZ3XTaZkC08rbsXBIiSeSivcFL920MUKnqju56Wg0QtP6vEMS521QzGre92f5jTVHB0kBpvScnQdpFZjYzOcE7wEBfV01QDoDhu14IgnSg9C1Kr8KTldYFjMD9mJsBuQAoKQbWGtgfd02QR4HBgzTMkCOV9kQXBRsZgt67Z1iQ9+atbQgdKCfMHPME0VBo0CGLHmCZLBQcIgRJtTE31fxRymgkSWDjsIsG5ioFT/iD35n66TGRquTfCFofXbd90Hw7sB3yXW/jlz3+xg8T3Bp1f0bZZj8AE+prg0cQtcWnF/VNW0XLrah9zGn2mpF8gByKgVvp+BPQM8NdCxXc6vdc3IPi0WIXMP+AQyloWulyd7tjbn921PL7weDsXKtxOeeSQAAAAZiS0dEAAAAAAAA+UO7fwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAAd0SU1FB+gHEgYwIwdfS3gAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAJo0lEQVR42u3dfWhV9R/A8Y/LXDZtkeYSVNZ6IEk0VvZHIEFID5ZBD1CZpcH+MNOCMap/+qMW6+mfsqxVSxb9oRFFD2QWFbUi1uyBZo8wsstWzaZbtFrbzev5/dGvUneuOd3W9L5ecCC+N+45nnHf3PO9537vuIhIAuAQUOQUAIIFIFiAYAEIFoBgAYIFIFgAggUIFoBgAQgWIFgAggUgWIBgAQgWgGABggUgWACCBQgWgGABCBYgWACCBSBYgGABCBYgWACCBSBYgGABCBaAYAGCBSBYAIIFCBaAYAEIFiBYAIIFIFiAYAEIFoBgAYIFIFgAggUIFoBgAQgWIFgAggUgWIBgAQgWIFgAggUgWIBgAQgWgGABggUgWACCBQgWgGABCBYgWACCBSBYgGABCBaAYAGCBSBYAIIFCBaAYAEIFiBYAIIFIFjAoWV8If1jH3jggSgq2rPRmUwm1qxZMyL7u/322+P444/fY6yvry/uuOOOgz7u4dTT0xN33333mDt/Y23/++vqq6+OhQsXRnl5ecyYMSNKS0ujuLg4JkyYEEcccURks9kYGBiI33//PTo7OyOTyURra+t+/Q2ISAply+Vyyd5aWlpGbH9tbW2D9tfd3T0sxz2cMpnMmDx/Y23/+9oWLFiQvPDCC0kmkzngv9fPP/+cNDU1JStXrkwK6XU5lM0lIRyE+fPnxyuvvBKbNm2Kyy67LGbNmnXA74ZLS0tjwYIF8fDDD8fHH38cy5cvd4LNYcHwqKmpiY0bN8Yll1wSRx999PC9KIuKorKyMurr66OxsdGJFiw4OI2NjVFXVxdTp07d/7mXJIk//vhjv///4uLiWLZsWXz00UcxY8YMJz0KbNL9cNLd3R3t7e3D8lw//PCDEzoEzzzzTCxZsmSfl369vb3x+eefxzfffBOfffZZtLa2xubNm6O3tzcmT54c5513XsybNy/mzZsXs2fPjlNOOSXGj09/OZ555pmxcePGWLRoUXR0dIRJd5Puh9yk+38x2WzSPZJHH310n5Pq27ZtS9auXZtMmzZtSM97wQUXJG+++WYyMDCQ97nHygcM//EmWIIlWPuzXXfddUlfX19qTHK5XPLGG28MOVR7b1VVVUlHR0feaK1bt86nhMC/u/POO2PixImDxnO5XDz55JNx/vnnx08//XRQ+2hoaIgrr7wytm7dmvr4kiVL4pprrjHpDuT34IMPxoknnpj62LPPPhsrVqwYtn01NzdHVVVV7NixI3Uivrq6WrCA/K666qrU8S1btsS111477Pt7++23Y926damPVVZWxurVqwULGKy2tjZOOOGEQePZbHbIX7MailtvvTW+/PLL1Pu0CvWyULDgX1x44YWp4y0tLfHSSy+N6L4feeSR1PEzzjgj5s6dK1jAPyoqKmLOnDmR736skfbYY4+l3ns1ceLEWLlypWAB/1i6dGkcddRRg8Y7OzvjiSeeGJVjaGlpyfsuS7CA2P3LzWm++OKLUTuG5557LnX8pJNOEixgz0vCNGmT4SNlw4YN8euvvw4anzp1alx88cWCBfwThTTvvvvuqB7Htm3bUsfPOusswQL+dOyxx0ba7QzPP//8qB7H999/nzqe72bWsFrD4WnWrFnx3nvvjchzT5kyZcwfd1tbW9xwww3KlGLx4sUxYcKEQeO//PLLqB9LT09P6nja/WGCdRgrKyuLsrKygj3uY445RpnymDZtWup4X1/fqB/Lb7/9ljqe9t1Gl4RQgEpLS1PHs9nsmAlWcXGxYAERJSUlqeM7d+4c9WPp7e1NHU+7ZBUsKEC5XC51fNy4caP/Qs2zummSJGEOq4B899130dTUFCP1HbR88yAxDJPlr7/++kE/jyV38+vv708dP/LII0f9WPLNVQ1ljXjBOgx0dXXFsmXLRiwqIxWsnp6eWLVqlarEyK6bP1Yuw9K+HhQRMTAw4JIQiPjkk09irHyyuvcviP/b7Q6CBQWmtbU19RaGSZMmjfrPbuW7p6+rq0uwgPyXhUVFRXHFFVfEaN93l+arr74SLCD+XkYm/uPv8E2ePDmmT58eabdXvPjii4IFxN8fnKQ57bTTRu0YbrrpptQbRH/88cf49ttvBQv4U75bXmbPnj1inwDvbeHChanjX3/9dVgPC4jdlyhOm8cqKSmJ2267bVQuB/OtLPrOO+8IFrCnTz/9NHX80ksvHfF933PPPamfEG7fvj3q6uoEC9jT+vXrY9euXYPGTz755KitrY2RXC3i8ssvT33s/fffDz/zBQzy1FNP5V0S+cYbb4zKysoR2W9DQ0Pqp4N9fX1x3333CRaQrr6+PnWVhilTpsTTTz897BPw999/f1x00UWpj23atCmam5sFC0i3du3a+OCDD1IfmzNnTrz22mtx6qmnDsu+7r333rj55ptj/PjxqfeF3XLLLQX7dxAs2E+rV6/OeyNpZWVlvPXWWwf146YVFRXx8ssvR01NTep9V9lsNurq6gp+hY2kULZcLpfsraWlZcT219bWNmh/3d3dY/64x+pxjIXzUFVVlfT29ib57Ny5M2lubk5qamr2+znPOeecpLGxMdm+fXve583lckl9fX1SSK/XtG3c//8jCmVBtr0XQtu8eXOcffbZMVJ3Se/9Y5c9PT1x3HHHHfRxZ7PZYVtapL29PU4//fQDOo5cLpd3obuhGhgY2OdKCP/1/v9SXV0dd911V94VSWO3Ww8ymUxkMpno6uqK3t7eyGazMWnSpCgtLY2ZM2fGzJkzo7y8fJ9rbO3atSvWr18fS5cu9TbXO6xD8x3WcMpkMmPiOPr7+8f0/nffli9fnnR2diYjrb+/P3nooYcK/p3VX5s5LDgAjY2NsWjRomhqahq2d3h727p1a6xataqgJ9lNukMM3wJ/5557blRXV8eWLVtSby6NA1wh4vHHH4+KiopoaGhwogULhs+aNWti7ty5cf3118err74aHR0dQ/5lnZ6envjwww+jtrY2pk+fHitWrHBiUxTUpDuMlvnz58fixYujvLw8ysrKoqSkJIqLi6OoqOjvD0x27NgR7e3t0dzcHBs2bHDSBAtwSQggWACCBQgWgGABCBYgWACCBSBYgGABCBYgWE4BIFgAggUIFoBgAQgWIFgAggUgWIBgAQgWgGABggUgWACCBQgWgGABCBYgWACCBSBYgGABCBaAYAGCBSBYAIIFCBaAYAGCBSBYAIIFCBaAYAEIFiBYAIIFIFiAYAEIFoBgAYIFIFgAggUIFoBgAQgWIFgAggUgWIBgAQgWgGABggUgWACCBRxi/gdCl93CoIt4dQAAAABJRU5ErkJggg==",
                'media_type' => 'image/png',
            ],
        ]]);
        $this->assertCount(1, $messages->messages());
    }

    public function test_joins_continuous_messages_with_the_same_role()
    {
        $messages = new Messages();
        $messages->addMessage(Messages::ROLE_USER, 'Hello');
        $messages->addMessage(Messages::ROLE_USER, 'How are you?');
        $messages->addMessage(Messages::ROLE_ASSISTANT, 'I am fine');
        $result = $messages->messages();
        $this->assertCount(2, $result);
        $this->assertCount(2, $result[0]['content']);
        $this->assertCount(1, $result[1]['content']);
    }
}